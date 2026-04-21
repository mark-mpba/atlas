<?php

namespace Modules\Core\Helpers;

/**
 * Class ModuleHelper
 */
class Helpers
{
    /**
     * Build a slug from a string.
     *
     * @param string $value
     * @return string
     */
    public static function slugify(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
        return trim($value, '-');
    }
}
