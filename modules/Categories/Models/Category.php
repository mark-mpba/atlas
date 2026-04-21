<?php

namespace Modules\Categories\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    public const string TABLE_NAME = 'categories';
    public $table = self::TABLE_NAME;

    protected $fillable = [
        'key',
        'value',
        'type',
        'deleted_at'
    ];
}
