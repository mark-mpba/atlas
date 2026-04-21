<?php

namespace Modules\Admin\Support;

class ModuleInfo
{
    /**
     * Get the module name.
     *
     * @return string
     */
    public static function name(): string
    {
        return 'Admin';
    }

    /**
     * Get the lowercase module name.
     *
     * @return string
     */
    public static function nameLower(): string
    {
        return 'admin';
    }
}
