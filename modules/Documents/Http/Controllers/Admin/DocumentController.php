<?php

namespace Modules\Documents\Http\Controllers\Admin;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Auditable\Services\AuditService;
use Modules\Core\Helpers\Helpers;
use Modules\Documents\Models\Document;
use Modules\Documents\Repositories\DocumentRepository;
use Modules\Documents\Services\DocumentRenderService;

/**
 * Class DocumentController
 */
class DocumentController extends Controller
{
    /**
     * DocumentController constructor.
     *
     * @param DocumentRepository $documentRepository
     * @param DocumentRenderService $documentRenderService
     * @param AuditService $auditService
     */
    public function __construct(
        protected DocumentRepository $documentRepository,
        protected DocumentRenderService $documentRenderService,
        protected AuditService $auditService
    ) {
    }

    /**
     * Display the document listing.
     *
     * @return View
     */
    public function index(): View
    {
        return view('documents::admin.index');
    }

    /**
     * Show the create form.
     *
     * @return View
     */
    public function create(): View
    {
        return view('documents::admin.create');
    }

    /**
     * Store a new document.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'markdown_body' => ['required', 'string'],
            'status' => ['required', 'string'],
        ]);

        $document = Document::query()->create([
            'title' => $validated['title'],
            'slug' => Helpers::slugify($validated['title']),
            'markdown_body' => $validated['markdown_body'],
            'html_body' => $this->documentRenderService->render($validated['markdown_body']),
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        $this->auditService->log(
            'Documents',
            Document::class,
            (int) $document->id,
            'created',
            null,
            $document->toArray(),
            auth()->id()
        );

        return redirect()
            ->route('documents.admin.index')
            ->with('success', 'Document created successfully.');
    }
}
