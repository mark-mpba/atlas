<?php
use Modules\Users\Support\ModuleInfo;

if (! function_exists('users_feature_enabled')) {
    /**
     * Check if a feature is enabled in the config.
     */
    function users_feature_enabled(string $key): bool
    {
        return (bool) config(ModuleInfo::nameLower().".features.{$key}", false);
    }
}
