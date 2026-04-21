<?php

namespace Modules\Auditable\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    public const string TABLE_NAME = 'audit_logs';
    public $table = self::TABLE_NAME;

    /**
     * @var array<int, string>
     */

    protected $fillable = [
        'module',
        'entity_type',
        'entity_id',
        'action',
        'old_values',
        'new_values',
        'performed_by',
    ];

    /**
     * @var array<string, string>
     */

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}
