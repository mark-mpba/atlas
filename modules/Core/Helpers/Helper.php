<?php
use Modules\Core\Support\ModuleInfo;

if (! function_exists('core_feature_enabled')) {
    /**
     * Check if a feature is enabled in the config.
     */
    function core_feature_enabled(string $key): bool
    {
        return (bool) config(ModuleInfo::nameLower().".features.{$key}", false);
    }
}

