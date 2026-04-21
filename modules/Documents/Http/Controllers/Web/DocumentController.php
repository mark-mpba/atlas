<?php

namespace Modules\Documents\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
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
            'navigation' => $this->buildNavigation(null, true),
        ]);
    }

    /**
     * Display a single document.
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        $document = Document::query()
            ->with('category')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        return view('documents::web.show', [
            'document' => $document,
            'navigation' => $this->buildNavigation($document->slug, false),
        ]);
    }

    /**
     * Build the sidebar navigation from categories and published documents.
     *
     * @param string|null $activeSlug
     * @param bool $expandAll
     * @return array<int, array<string, mixed>>
     */
    protected function buildNavigation(?string $activeSlug = null, bool $expandAll = false): array
    {
        $categories = Category::query()
            ->with([
                'documents' => function ($query): void {
                    $query->where('status', 'published')
                        ->orderBy('title');
                },
            ])
            ->orderBy('name')
            ->get();

        return $categories->map(function (Category $category) use ($activeSlug, $expandAll): array {
            $children = $category->documents->map(function (Document $document) use ($activeSlug): array {
                return [
                    'title' => $document->title,
                    'url' => route('documents.web.show', $document->slug),
                    'active' => $document->slug === $activeSlug,
                ];
            })->values()->all();

            $hasActiveChild = collect($children)->contains(fn (array $child): bool => $child['active']);

            return [
                'title' => $category->name,
                'active' => $expandAll || $hasActiveChild,
                'children' => $children,
            ];
        })->filter(function (array $section): bool {
            return !empty($section['children']);
        })->values()->all();
    }
}
