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
    public function __invoke(): View
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
                        ->where(function ($innerQuery): void {
                            $innerQuery->whereNull('is_home')
                                ->orWhere('is_home', false);
                        })
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
            ->where(function ($query): void {
                $query->whereNull('is_home')
                    ->orWhere('is_home', false);
            })
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
     * Remove a leading title from the rendered source when it matches the seeded document title.
     *
     * This checks the first few leading markdown lines or the first few leading HTML heading
     * blocks and removes the first matching title occurrence. Home documents are handled
     * by the caller and should skip this method.
     *
     * @param string $content
     * @param string $documentTitle
     * @param bool $isHtml
     * @return string
     */
    protected function removeLeadingDocumentTitle(string $content, string $documentTitle, bool $isHtml = false): string
    {
        $content = ltrim($content);
        $documentTitle = $this->normaliseDocumentTitle($documentTitle);

        if ($documentTitle === '') {
            return $content;
        }

        if ($isHtml) {
            return $this->removeLeadingDocumentTitleFromHtml($content, $documentTitle);
        }

        return $this->removeLeadingDocumentTitleFromMarkdown($content, $documentTitle);
    }

    /**
     * Remove a leading matching title from markdown content.
     *
     * Looks only at the first few non-empty lines.
     *
     * @param string $content
     * @param string $documentTitle
     * @return string
     */
    protected function removeLeadingDocumentTitleFromMarkdown(string $content, string $documentTitle): string
    {
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content) ?? $content;

        $lines = preg_split("/\R/u", $content) ?: [];
        $maxLinesToInspect = min(6, count($lines));

        for ($index = 0; $index < $maxLinesToInspect; $index++) {
            $line = trim($lines[$index]);

            if ($line === '') {
                continue;
            }

            $candidate = null;

            if (preg_match('/^#\s+(.+)$/u', $line, $matches)) {
                $candidate = $matches[1];
            } elseif (preg_match('/^\*\*(.+)\*\*$/u', $line, $matches)) {
                $candidate = $matches[1];
            } else {
                $candidate = $line;
            }

            if ($this->normaliseDocumentTitle($candidate) !== $documentTitle) {
                continue;
            }

            unset($lines[$index]);

            if (isset($lines[$index + 1]) && trim($lines[$index + 1]) === '') {
                unset($lines[$index + 1]);
            }

            return implode("\n", array_values($lines));
        }

        return $content;
    }

    /**
     * Remove a leading matching title from HTML content.
     *
     * Looks only at the first few heading blocks near the start of the HTML.
     *
     * @param string $content
     * @param string $documentTitle
     * @return string
     */
    protected function removeLeadingDocumentTitleFromHtml(string $content, string $documentTitle): string
    {
        if (preg_match_all('/<(h1|h2|h3)[^>]*>(.*?)<\/\1>/is', $content, $matches, PREG_OFFSET_CAPTURE)) {
            $count = min(3, count($matches[0]));

            for ($index = 0; $index < $count; $index++) {
                $fullMatch = $matches[0][$index][0];
                $fullOffset = $matches[0][$index][1];
                $headingInner = $matches[2][$index][0];

                if ($fullOffset > 1500) {
                    break;
                }

                $candidate = $this->normaliseDocumentTitle(
                    html_entity_decode(strip_tags($headingInner), ENT_QUOTES | ENT_HTML5)
                );

                if ($candidate !== $documentTitle) {
                    continue;
                }

                return substr($content, 0, $fullOffset)
                    . preg_replace('/^\s+/', '', substr($content, $fullOffset + strlen($fullMatch)));
            }
        }

        return $content;
    }

    /**
     * Normalise a title for loose comparison.
     *
     * @param string $value
     * @return string
     */
    protected function normaliseDocumentTitle(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5);
        $value = strip_tags($value);
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);

        return mb_strtolower($value);
    }

}
