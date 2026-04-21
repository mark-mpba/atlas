<?php
use Modules\CoreUI\Support\ModuleInfo;

if (! function_exists('coreui_feature_enabled')) {
    /**
     * Check if a feature is enabled in the config.
     */
    function coreui_feature_enabled(string $key): bool
    {
        return (bool) config(ModuleInfo::nameLower().".features.{$key}", false);
    }
}
