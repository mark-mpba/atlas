<?php

namespace Modules\Documents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\TracksUserStamps;

class Document extends Model
{
    use SoftDeletes;
    use TracksUserStamps;

    /**
     * @var string
     */

    public const string TABLE_NAME = 'documents';
    public $table = self::TABLE_NAME;

    /**
     * @var array<int, string>
     */
    protected $fillable = [

        'title',
        'slug',
        'excerpt',
        'markdown_body',
        'html_body',
        'status',
        'is_featured',
        'published_at',
        'meta_title',
        'meta_description',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * @var array<string, string>
     */

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];
}
