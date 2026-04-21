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
