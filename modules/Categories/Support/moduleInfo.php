<?php

namespace Modules\Categories\Support;

class moduleInfo
{
    protected static string $moduleName =  'Categories';

    /**
     * defines the Module Name
     * @return string
     */
    public static function name(): string
    {
        return static::$moduleName;
    }

    /**
     * define the lowercase version
     * @return string
     */
    public static function nameLower(): string
    {
        return strtolower(static::$moduleName);
    }
}
