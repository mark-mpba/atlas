<?php

namespace Modules\Categories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Documents\Models\Document;

class Category extends Model
{
    use SoftDeletes;
    public const string TABLE_NAME = 'categories';
    public $table = self::TABLE_NAME;

    protected $fillable = [
        'name',
        'desciprion',
        'slug',
        'type',
        'deleted_at'
    ];

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'category_id', 'id');
    }
}
