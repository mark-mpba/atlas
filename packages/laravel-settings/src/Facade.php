<?php

namespace mpba\Settings;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'mpba\Settings\Setting\SettingStorage';
    }
}
