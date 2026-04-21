<?php
use Modules\Auditable\Support\ModuleInfo;

if (! function_exists('auditable_feature_enabled')) {
    /**
     * Check if a feature is enabled in the config.
     */
    function auditable_feature_enabled(string $key): bool
    {
        return (bool) config(ModuleInfo::nameLower().".features.{$key}", false);
    }
}
