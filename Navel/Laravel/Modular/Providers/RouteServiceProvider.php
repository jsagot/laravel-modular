<?php

namespace Navel\Laravel\Modular\Providers;

use Illuminate\Foundation\Application;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

abstract class RouteServiceProvider extends ServiceProvider
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
    protected $module = 'Module';

    /**
     * The module routes prefix.
     *
     * @var string
     */
    protected $prefix = 'module';

    /**
     * [DO NOT OVERRIDE THIS PROPERTY]
     * This namespace is applied to your module.
     *
     * @var string
     */
    protected $namespace;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();

        // $this->mapAnyRoutes()
        // ...
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        if($this->prefix === 'module') {
            Route::middleware('web')
                 ->namespace($this->namespace.'Controllers')
                 ->group($this->path.'/../routes/web.php');
                 return;
        }
        Route::middleware('web')
             ->namespace($this->namespace.'Controllers')
             ->prefix($this->prefix)
             ->group($this->path.'/../routes/web.php');
    }

    public function __construct(Application $app)
    {
        $this->namespace = 'Modules\\'.$this->module.'\\';
        parent::__construct($app);
    }
}
