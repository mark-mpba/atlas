<?php
use Modules\Categories\Support\ModuleInfo;

if (! function_exists('categories_feature_enabled')) {
    /**
     * Check if a feature is enabled in the config.
     */
    function categories_feature_enabled(string $key): bool
    {
        return (bool) config(ModuleInfo::nameLower().".features.{$key}", false);
    }
}
