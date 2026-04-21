<?php
use Modules\Documents\Support\ModuleInfo;

if (! function_exists('documents_feature_enabled')) {
    /**
     * Check if a feature is enabled in the config.
     */
    function documents_feature_enabled(string $key): bool
    {
        return (bool) config(ModuleInfo::nameLower().".features.{$key}", false);
    }
}
