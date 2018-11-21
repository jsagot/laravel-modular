<?php

namespace Modules\Demo\Facades;

class Dummy extends \Illuminate\Support\Facades\Facade
{
    public static function getFacadeAccessor() { return 'dummy'; }
}
