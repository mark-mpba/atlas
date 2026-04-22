<?php

namespace Modules\Documents\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Categories\Models\Category;
use Modules\Documents\Models\Document;

/**
 * Class DocumentController
 */
class DocumentController extends Controller
{
    /**
     * Display the documentation index.
     *
     * @return View
     */
    public function index(): View
    {
        $documents = Document::query()
            ->with('category')
            ->where('status', 'published')
            ->orderBy('title')
            ->get();


        return view('documents::web.index', [
            'documents' => $documents,
            'navigation' => $this->buildNavigation(null, false),
        ]);
    }

    /**
     * Display the requested document.
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        $document = Document::query()
            ->with('category')
            ->where('slug', $slug)
            ->firstOrFail();

        $previousDocument = Document::query()
            ->where('id', '<', $document->id)
            ->orderByDesc('id')
            ->first();

        $nextDocument = Document::query()
            ->where('id', '>', $document->id)
            ->orderBy('id')
            ->first();

        $docSections = Category::query()
            ->orderBy('name')
            ->get()
            ->map(function (Category $category) use ($document): array {
                return [
                    'label' => $category->name,
                    'url' => route('documents.web.index', ['category' => $category->slug]),
                    'active' => (int) $document->category_id === (int) $category->id,
                ];
            })
            ->all();

        $relatedDocuments = Document::query()
            ->where('category_id', $document->category_id)
            ->whereKeyNot($document->id)
            ->limit(5)
            ->get()
            ->map(function (Document $related): array {
                return [
                    'title' => $related->title,
                    'url' => route('documents.web.show', $related->slug),
                ];
            })
            ->all();

        $renderedContent = $this->resolveRenderedContent($document);

        return view('documents::web.show', [
            'document' => $document,
            'previousDocument' => $previousDocument,
            'nextDocument' => $nextDocument,
            'docSections' => $docSections,
            'relatedDocuments' => $relatedDocuments,
            'renderedContent' => $renderedContent,
            'navigation' => $this->buildNavigation($document->slug, false),
        ]);
    }

    /**
     * Resolve renderable HTML for a document.
     *
     * @param Document $document
     * @return string
     */
    /**
     * Resolve renderable HTML for a document.
     *
     * @param Document $document
     * @return string
     */
    /**
     * Resolve renderable HTML for a document.
     *
     * @param Document $document
     * @return string
     */
    protected function resolveRenderedContent(Document $document): string
    {
        $preserveLeadingTitle = (bool) ($document->is_home ?? false);

        if (! empty($document->html_body)) {
            $html = (string) $document->html_body;

            return $preserveLeadingTitle
                ? $html
                : $this->removeLeadingDocumentTitle($html, (string) $document->title, true);
        }

        if (! empty($document->markdown_body)) {
            $markdown = (string) $document->markdown_body;

            if (! $preserveLeadingTitle) {
                $markdown = $this->removeLeadingDocumentTitle($markdown, (string) $document->title, false);
            }

            return Str::markdown($markdown);
        }

        if (! empty($document->content)) {
            $markdown = (string) $document->content;

            if (! $preserveLeadingTitle) {
                $markdown = $this->removeLeadingDocumentTitle($markdown, (string) $document->title, false);
            }

            return Str::markdown($markdown);
        }

        $sourcePath = $document->source_path ?? null;

        if (! empty($sourcePath) && Storage::disk('docs')->exists($sourcePath)) {
            $markdown = (string) Storage::disk('docs')->get($sourcePath);

            if (! $preserveLeadingTitle) {
                $markdown = $this->removeLeadingDocumentTitle($markdown, (string) $document->title, false);
            }

            return Str::markdown($markdown);
        }

        throw new \RuntimeException(
            'No renderable content or markdown source path is configured for document [' . $document->slug . '].'
        );
    }

    /**
     * Build the sidebar navigation from categories and published documents.
     *
     * @param string|null $activeSlug
     * @param bool $expandAll
     * @return array<string, mixed>
     */
    protected function buildNavigation(?string $activeSlug = null, bool $expandAll = false): array
    {
        $activeDocument = null;

        if ($activeSlug !== null) {
            $activeDocument = Document::query()
                ->where('slug', $activeSlug)
                ->first();
        }

        $collapseForHomeDocument = (bool) ($activeDocument?->is_home ?? false);

        $categories = Category::query()
            ->with([
                'documents' => function ($query): void {
                    $query->where('status', 'published')
                        ->orderBy('title');
                },
            ])
            ->orderBy('name')
            ->get();

        $sections = $categories->map(function (Category $category) use ($activeSlug, $expandAll, $collapseForHomeDocument): array {
            $children = $category->documents->map(function (Document $document) use ($activeSlug): array {
                return [
                    'title' => $document->title,
                    'url' => route('documents.web.show', $document->slug),
                    'active' => $document->slug === $activeSlug,
                    'is_favourite' => (bool) ($document->is_favourite ?? false),
                ];
            })->values()->all();

            $hasActiveChild = collect($children)->contains(function (array $child): bool {
                return $child['active'];
            });

            return [
                'title' => $category->name,
                'expanded' => $expandAll || ($hasActiveChild && ! $collapseForHomeDocument),
                'children' => $children,
            ];
        })->filter(function (array $section): bool {
            return ! empty($section['children']);
        })->values()->all();

        $favourites = Document::query()
            ->where('status', 'published')
            ->where('is_favourite', true)
            ->orderBy('title')
            ->get()
            ->map(function (Document $document) use ($activeSlug): array {
                return [
                    'title' => $document->title,
                    'url' => route('documents.web.show', $document->slug),
                    'active' => $document->slug === $activeSlug,
                ];
            })
            ->values()
            ->all();

        return [
            'sections' => $sections,
            'favourites' => $favourites,
        ];
    }

    /**
     * Get the configured home document.
     *
     * @return Document|null
     */
    protected function getHomeDocument(): ?Document
    {
        return Document::query()
            ->where('status', 'published')
            ->where('is_home', true)
            ->orderByDesc('id')
            ->first();

    }

    /**
     * Redirect to the configured home document.
     *
     * @return RedirectResponse
     */

    public function home():RedirectResponse
    {
        $homeDocument = $this->getHomeDocument();
        if ($homeDocument !== null) {
            return redirect()->route('documents.web.show', $homeDocument->slug);
        }
        return redirect()->route('documents.web.index');
    }


    /**
     * Remove the first heading only if it matches the document title.
     *
     * @param string $content
     * @param string $documentTitle
     * @param bool $isHtml
     * @return string
     */
    protected function removeLeadingDocumentTitle(string $content, string $documentTitle, bool $isHtml = false): string
    {
        $content = ltrim($content);
        $documentTitle = trim($documentTitle);

        if ($documentTitle === '') {
            return $content;
        }

        if ($isHtml) {
            if (preg_match('/^\s*<h1[^>]*>(.*?)<\/h1>\s*/is', $content, $matches)) {
                $headingText = trim(strip_tags(html_entity_decode($matches[1], ENT_QUOTES | ENT_HTML5)));

                if (strcasecmp($headingText, $documentTitle) === 0) {
                    return preg_replace('/^\s*<h1[^>]*>.*?<\/h1>\s*/is', '', $content, 1) ?? $content;
                }
            }

            return $content;
        }

        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content) ?? $content;

        if (preg_match('/^\s*#\s+(.+?)\R+/u', $content, $matches)) {
            $headingText = trim($matches[1]);

            if (strcasecmp($headingText, $documentTitle) === 0) {
                return preg_replace('/^\s*#\s+.+\R+/u', '', $content, 1) ?? $content;
            }
        }

        return $content;
    }
}
