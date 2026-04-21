<?php

namespace Modules\Auditable\Services;

use Modules\Auditable\Models\Audit;

/**
 * Class AuditService
 */
class AuditService
{
    /**
     * Write an audit log entry.
     *
     * @param string $module
     * @param string $entityType
     * @param int|null $entityId
     * @param string $action
     * @param array<mixed>|null $oldValues
     * @param array<mixed>|null $newValues
     * @param int|null $performedBy
     * @return Audit
     */
    public function log(
        string $module,
        string $entityType,
        ?int $entityId,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $performedBy = null
    ): Audit {
        return Audit::query()->create([
            'module' => $module,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'performed_by' => $performedBy,
        ]);
    }
}
