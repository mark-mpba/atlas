<?php
use Modules\Settings\Support\ModuleInfo;

if (! function_exists('settings_feature_enabled')) {
    /**
     * Check if a feature is enabled in the config.
     */
    function settings_feature_enabled(string $key): bool
    {
        return (bool) config(ModuleInfo::nameLower().".features.{$key}", false);
    }
}
