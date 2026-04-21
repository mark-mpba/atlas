<?php

namespace Modules\Documents\Repositories;

use Illuminate\Database\Eloquent\Builder;
use Modules\Documents\Models\Document;

/**
 * Class DocumentRepository
 */
class DocumentRepository
{
    /**
     * Get base query for documents.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return Document::query();
    }

    /**
     * Find document by id.
     *
     * @param int $id
     * @return Document|null
     */
    public function findById(int $id): ?Document
    {
        return Document::query()->find($id);
    }

    /**
     * Find published document by slug.
     *
     * @param string $slug
     * @return Document|null
     */
    public function findPublishedBySlug(string $slug): ?Document
    {
        return Document::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();
    }
}
