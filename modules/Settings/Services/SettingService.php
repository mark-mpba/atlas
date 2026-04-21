<?php

namespace Modules\Settings\Services;

use Modules\Settings\Models\Setting;

/**
 * Class SettingService
 */
class SettingService
{
    /**
     * Get a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $setting = Setting::query()->where('key', $key)->first();

        return $setting?->value ?? $default;
    }

    /**
     * Set a setting value.
     *
     * @param string $key
     * @param mixed $value
     * @param string $type
     * @return Setting
     */
    public function set(string $key, mixed $value, string $type = 'string'): Setting
    {
        return Setting::query()->updateOrCreate(
            ['key' => $key],
            [
                'value' => is_scalar($value) ? (string) $value : json_encode($value),
                'type' => $type,
            ]
        );
    }
}
