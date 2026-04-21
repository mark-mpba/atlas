<?php
use Modules\Admin\Support\ModuleInfo;

if (! function_exists('admin_feature_enabled')) {
    /**
     * Check if a feature is enabled in the config.
     */
    function admin_feature_enabled(string $key): bool
    {
        return (bool) config(ModuleInfo::nameLower().".features.{$key}", false);
    }
}
