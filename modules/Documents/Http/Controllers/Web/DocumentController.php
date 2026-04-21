<?php

namespace Modules\Documents\Http\Controllers\Web;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Modules\Documents\Models\Document;
use Modules\Documents\Repositories\DocumentRepository;

/**
 * Class DocumentController
 */
class DocumentController extends Controller
{
    /**
     * DocumentController constructor.
     *
     * @param DocumentRepository $documentRepository
     */
    public function __construct(
        protected DocumentRepository $documentRepository
    ) {
    }

    /**
     * Display published documents.
     *
     * @return View
     */
    public function index(): View
    {
        $documents = Document::query()
            ->where('status', 'published')
            ->latest('published_at')
            ->get();

        return view('documents::web.index', compact('documents'));
    }

    /**
     * Display a published document.
     *
     * @param string $slug
     * @return View
     */
    public function show(string $slug): View
    {
        $document = $this->documentRepository->findPublishedBySlug($slug);

        abort_if(!$document, 404);

        return view('documents::web.show', compact('document'));
    }
}
