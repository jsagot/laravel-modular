<?php

namespace Modules\Demo\Providers;

use Navel\Laravel\Modular\Providers\RouteServiceProvider as ServiceProvider;

class DemoRouteServiceProvider extends ServiceProvider
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
    protected $module = 'Demo';

    /**
     * The module routes prefix.
     *
     * @var string
     */
    protected $prefix = 'demo';

}
