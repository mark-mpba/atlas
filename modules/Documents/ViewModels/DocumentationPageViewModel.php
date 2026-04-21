<?php

namespace Modules\Documents\ViewModels;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Categories\Models\Category;
use Modules\Documents\Models\Document;

class DocumentationPageViewModel
{
    /**
     * @var string|null
     */
    private ?string $slug;

    /**
     * @var Document|null
     */
    private ?Document $document = null;

    /**
     * @var array<int, array<string, mixed>>
     */
    public array $navigation = [];

    /**
     * @var array<int, array<string, string>>
     */
    public array $breadcrumbs = [];

    /**
     * @var string
     */
    public string $pageTitle = 'Documentation';

    /**
     * @var string
     */
    public string $pageHeading = 'Documentation';

    /**
     * @var string
     */
    public string $html = '';

    /**
     * @param string|null $slug
     */
    public function __construct(?string $slug = null)
    {
        $this->slug = $slug;
    }

    /**
     * Build the home page from the document flagged as home.
     *
     * @return self
     */
    public function buildHome(): self
    {
        $this->document = Document::query()
            ->with('category')
            ->where('is_home', true)
            ->where('status', 'published')
            ->first();

        if ($this->document === null) {
            throw new ModelNotFoundException('No home documentation page is defined.');
        }

        return $this->buildFromDocument();
    }

    /**
     * Build the page by slug.
     *
     * @return self
     */
    public function build(): self
    {
        $this->document = Document::query()
            ->with('category')
            ->where('slug', $this->slug)
            ->where('status', 'published')
            ->firstOrFail();

        return $this->buildFromDocument();
    }

    /**
     * Convert the ViewModel to an array for the Blade.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'navigation' => $this->navigation,
            'breadcrumbs' => $this->breadcrumbs,
            'pageTitle' => $this->pageTitle,
            'pageHeading' => $this->pageHeading,
            'html' => $this->html,
            'document' => $this->document,
        ];
    }

    /**
     * Build the full page state from the current document.
     *
     * @return self
     */
    private function buildFromDocument(): self
    {
        $this->navigation = $this->buildNavigation();
        $this->breadcrumbs = $this->buildBreadcrumbs();
        $this->pageTitle = $this->document?->meta_title ?: $this->document?->title ?: 'Documentation';
        $this->pageHeading = $this->document?->title ?: 'Documentation';
        $this->html = (string) ($this->document?->html_body ?: '');

        return $this;
    }

    /**
     * Build the left-hand navigation tree.
     *
     * @return array<int, array<string, mixed>>
     */
    private function buildNavigation(): array
    {
        $categories = Category::query()
            ->with([
                'documents' => function ($query): void {
                    $query->where('status', 'published')
                        ->where('show_in_nav', true)
                        ->orderBy('sort_order')
                        ->orderBy('title');
                },
            ])
            ->where('show_in_nav', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return $categories->map(function (Category $category): array {
            $children = $category->documents->map(function (Document $document): array {
                return [
                    'title' => $document->title,
                    'url' => route('documents.web.show', ['slug' => $document->slug]),
                    'active' => $this->document !== null && $document->id === $this->document->id,
                ];
            })->values()->toArray();

            return [
                'title' => $category->name,
                'active' => $this->document !== null && (int) $category->id === (int) $this->document->category_id,
                'children' => $children,
            ];
        })->values()->toArray();
    }

    /**
     * Build breadcrumbs for the current page.
     *
     * @return array<int, array<string, string>>
     */
    private function buildBreadcrumbs(): array
    {
        $breadcrumbs = [
            [
                'title' => 'Home',
                'url' => route('documents.web.index'),
            ],
        ];

        if ($this->document?->category !== null) {
            $breadcrumbs[] = [
                'title' => $this->document->category->name,
                'url' => '#',
            ];
        }

        if ($this->document !== null) {
            $breadcrumbs[] = [
                'title' => $this->document->title,
                'url' => route('documents.web.show', ['slug' => $this->document->slug]),
            ];
        }

        return $breadcrumbs;
    }
}
