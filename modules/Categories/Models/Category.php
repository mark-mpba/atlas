<?php

namespace Modules\Categories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Documents\Models\Document;

/**
 * Class Category
 */
class Category extends Model
{
    use SoftDeletes;

    public const string TABLE_NAME = 'categories';

    /**
     * @var string
     */
    public $table = self::TABLE_NAME;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'slug',
        'type',
        'sort_order',
        'show_in_nav',
    ];

    /**
     * @var array<string, string>
     */

    protected $casts = [
        'show_in_nav' => 'boolean',
        'sort_order'  => 'integer',
    ];

    /**
     * Get the documents for the category.
     *
     * @return HasMany
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id', 'id');
    }
}
