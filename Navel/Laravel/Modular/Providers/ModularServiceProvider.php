<?php

namespace Navel\Laravel\Modular\Providers;


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

use Navel\Laravel\Modular\Console\Commands\ModuleMakeCommand;
use Navel\Laravel\Modular\Console\Commands\ModularDemoCommand;

/** */
class ModularServiceProvider extends ServiceProvider
{
    /**
     * Is Modules folder scanned ?
     *
     * @var bool
     */
    protected $scanned = false;

    /**
     * Modules folders list
     *
     * @var array
     */
    protected $modules = [];

    /**
     * Modules folders list
     *
     * @var array
     */
    protected $kernels = [];

    /**
     * Modules folders list
     *
     * @var Router
     */
    protected $router;

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/modular.php' => config_path('modular.php'),
        ], 'modular.config');

        if(!$this->check()) { return; }

        /* Artisan commands */
        if ($this->app->runningInConsole()) {
            $this->commands([
                ModuleMakeCommand::class,
                ModularDemoCommand::class,
            ]);
        }
        // */

        $this->registerMiddleware();
    }

    /**
     * Register any modules services.
     *
     * @return void
     */
    public function register()
    {
        if(!$this->check()) { return; }

        $this->router = $this->app['router'];

        $this->registerModules();
        $this->registerKernels();
    }

    /**
     * Register modules route middlewares.
     *
     * @param string $kernel
     * @return void
     */
    protected function registerRoutesMiddleware($kernel)
    {
        foreach ($kernel->routeMiddleware as $name => $class) {
            $this->router->aliasMiddleware($name, $class);
        }
    }

    /**
     * Register modules groups middlewares.
     *
     * @param string $kernel
     * @return void
     */
    protected function registerGroupsMiddleware($kernel)
    {
        foreach ($kernel->middlewareGroups as $group => $middleware) {
            // check if router has group then push to it
            if($this->router->hasMiddlewareGroup($group)) {
                // push to group
                foreach ($middleware as $push) {
                    $this->router->pushMiddlewareToGroup($group, $push);
                }
            } else {
                // create new group
                $this->router->middlewareGroup($group, $middleware);
            }
        }
    }

    /** */
    protected function registerKernels()
    {
        foreach ($this->kernels as $kernel) {
            $this->registerGroupsMiddleware($kernel);
            $this->registerRoutesMiddleware($kernel);
        }
    }

    /**
     *
     *
     * @param string $module
     * @return void
     */
    protected function configureModule($module)
    {
        $path = base_path(config('modular.path'));

        if(file_exists($path.'/'.$module.'/config/'.strtolower($module).'.php')) {
            $this->publishes([
                $path.'/'.$module.'/config/'.strtolower($module).'.php' => config_path(strtolower($module).'.php'),
            ], 'module.'.strtolower($module).'.config');
        }

        if(file_exists($path.'/'.$module.'/database/migrations'))  {
            $this->loadMigrationsFrom($path.'/'.$module.'/database/migrations');
        }


        if(file_exists($path.'/'.$module.'/resources/lang')) {
            $this->loadTranslationsFrom($path.'/'.$module.'/resources/lang', strtolower($module));
        }

        if(file_exists($path.'/'.$module.'/resources/views'))  {
            $this->loadViewsFrom($path.'/'.$module.'/resources/views', strtolower($module));
        }

    }

    /**
     *
     *
     * @param string $module
     * @return void
     */
    protected function registerAlias($module)
    {
        $path = base_path(config('modular.path')).'/'.$module.'/config/'.strtolower($module).'.php';
        if(file_exists($path)) {
            $config = require_once $path;
            if(isset($config['aliases'])) {
                foreach ($config['aliases'] as $abstract => $concrete) {
                    $this->app->alias($abstract, $concrete);
                }
            }
        }
    }

    /**
     * Register modules service providers.
     *
     * @param array $providers
     * @return void
     */
    protected function registerProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->app->register($provider, true);
        }
    }

    /**
     * @param string $module
     * @return array
     */
    protected function extractProviders($module)
    {
        $path = base_path(config('modular.path')).DIRECTORY_SEPARATOR.$module.DIRECTORY_SEPARATOR.'Providers';
        if(!file_exists($path)) {
            return []; // throw exception !!!
        }
        if(!is_dir($path)) {
            return []; // throw exception !!!
        }
        $providers = scandir($path);
        $providers = array_slice($providers, 2);

        array_walk($providers, function(&$value) use ($module) {
            $value = config('modular.namespace').$module.'\\Providers\\'.str_replace('.php', '', $value);
        });

        return $providers;
    }

    /**
     * Load modules kernels.
     *
     * @param string $module
     * @return void
     */
    protected function loadKernel($module)
    {
        $kernel = config('modular.namespace').$module.'\\Kernel';
        $this->kernels[] = new $kernel;
    }

    /** */
    protected function registerModules()
    {
        foreach ($this->modules as $module) {
            $this->loadKernel($module);
            $this->registerProviders($this->extractProviders($module));
            $this->registerAlias($module);
            $this->configureModule($module);
        }
    }

    /**
     * Register modules global middlewares.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        foreach ($this->kernels as $kernel) {
            foreach ($kernel->middleware as $middleware) {
                $this->app->make(Kernel::class)->pushMiddleware($middleware);
            }
        }
        // push global middleware
        //$this->app
        //     ->make(Illuminate\Contracts\Http\Kernel::class)
        //     ->pushMiddleware(\App\Http\Middleware\DemoMiddleware::class);

        // prepend global middleware
        //$this->app
        //     ->make(Illuminate\Contracts\Http\Kernel::class)
        //     ->prependMiddleware(\App\Http\Middleware\DemoMiddleware::class);
    }

    /**
     * Scan modules folder.
     *
     * @return void
     */
    protected function scan()
    {
        $modules = scandir(base_path(config('modular.path')));
        $this->modules = array_slice($modules, 2);
        $this->scanned = true;
    }

    /** @return bool */
    protected function activated()
    {
        return config('modular.active', false);
    }

    /** @return bool */
    protected function check()
    {
        if(!$this->activated()) {
            return false;
        }

        if(!$this->scanned) {
            $this->scan();
        }
        return true;
    }

}