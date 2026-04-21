<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Auth;

/**
 * Trait TracksUserStamps
 */
trait TracksUserStamps
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootTracksUserStamps(): void
    {
        static::creating(function ($model): void {
            if (Auth::check() && empty($model->created_by)) {
                $model->created_by = Auth::id();
            }

            if (Auth::check() && empty($model->updated_by)) {
                $model->updated_by = Auth::id();
            }
        });

        static::updating(function ($model): void {
            if (Auth::check()) {
                $model->updated_by = Auth::id();
            }
        });
    }
}
