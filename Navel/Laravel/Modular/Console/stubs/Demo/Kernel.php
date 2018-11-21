<?php

namespace Modules\Demo;

use Navel\Laravel\Modular\Kernel as BaseKernel;

/**
 *
 */
class Kernel extends BaseKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'demo' => \Modules\Demo\Middleware\DemoMiddleware::class,
    ];
}
