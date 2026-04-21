<?php

namespace Modules\Settings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\Traits\TracksUserStamps;

class Setting extends Model
{



    public const string TABLE_NAME = 'settings';
    public $table = self::TABLE_NAME;

    protected $fillable = [
        'key',
        'value',
        'type',
    ];
}
