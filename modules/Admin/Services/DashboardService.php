<?php

namespace Modules\Admin\Services;

use Modules\Documents\Models\Document;
use Modules\Users\Models\User;

/**
 * Class DashboardService
 */
class DashboardService
{
    /**
     * Get dashboard statistics.
     *
     * @return array<string, int>
     */
    public function getStats(): array
    {
        return [
            'documents_total' => class_exists(Document::class) ? Document::query()->count() : 0,
            'documents_published' => class_exists(Document::class)
                ? Document::query()->where('status', 'published')->count()
                : 0,
            'documents_draft' => class_exists(Document::class)
                ? Document::query()->where('status', 'draft')->count()
                : 0,
            'users_total' => class_exists(User::class) ? User::query()->count() : 0,
        ];
    }
}
