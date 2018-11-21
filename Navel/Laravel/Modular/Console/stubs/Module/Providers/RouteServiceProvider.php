<?php

namespace Modules\{module}\Providers;

use Navel\Laravel\Modular\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The module path.
     *
     * @var string
     */
    protected $path = __DIR__;

    /**
     * The module name.
     *
     * @var string
     */
    protected $module = '{module}';

    /**
     * The module routes prefix.
     *
     * @var string
     */
    protected $prefix = '{moduleName}';

}
