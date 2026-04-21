<?php

namespace Modules\Documents\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Modules\Categories\Models\Category;
use Modules\Documents\Models\Document;

class DocumentController extends Controller
{
    /**
     * Display the documentation index page.
     *
     * @return View
     */
    public function index(): View
    {
        $documents = Document::query()
            ->where('status', 'published')
            ->orderBy('title')
            ->get();

        return view('documents::web.index', [
            'documents' => $documents,
            'navigation' => $this->buildNavigation(),
        ]);
    }

    /**
     * Display a documentation page.
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
            'navigation' => $this->buildNavigation($document),
        ]);
    }

    /**
     * Build the sidebar navigation tree.
     *
     * @param Document|null $currentDocument
     * @return array<int, array<string, mixed>>
     */
    private function buildNavigation(?Document $currentDocument = null): array
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

        return $categories->map(function (Category $category) use ($currentDocument): array {
            return [
                'title' => $category->name,
                'active' => $currentDocument !== null
                    && (int) $currentDocument->category_id === (int) $category->id,
                'children' => $category->documents->map(function (Document $document) use ($currentDocument): array {
                    return [
                        'title' => $document->title,
                        'url' => route('documents.web.show', $document->slug),
                        'active' => $currentDocument !== null
                            && (int) $currentDocument->id === (int) $document->id,
                    ];
                })->values()->toArray(),
            ];
        })->values()->toArray();
    }
}
