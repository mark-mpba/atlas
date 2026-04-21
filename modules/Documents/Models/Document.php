<?php

namespace Modules\Documents\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Categories\Models\Category;

class Document extends Model
{
    use SoftDeletes;

    public const string TABLE_NAME = 'documents';

    /**
     * @var string
     */
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
        'category_id',
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

    /**
     * Get the category that owns the document.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
