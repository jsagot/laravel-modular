<?php

namespace Navel\Laravel\Modular\Providers;


use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

use Navel\Laravel\Autoloader;

use Navel\Laravel\Modular\Console\Commands\ModuleMakeCommand;
use Navel\Laravel\Modular\Console\Commands\ModularDemoCommand;

/** */
class ModularServiceProvider extends ServiceProvider
{
    /**
     * Router
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
        ], 'modular');

        if(!$this->active()) { return; }

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
        if(!$this->active()) { return; }
        $this->router = $this->app['router'];
        $this->registerModules();
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

    /**
     *
     *
     * @param string $module
     * @return void
     */
    protected function configureModule($module)
    {
        $path = base_path(config('modular.path'));

        if(!file_exists(app()->basePath().'/config/'.strtolower($module))) {
            $folder = $path.'/'.$module.'/config/';
            foreach (scandir($folder) as $file) {
                if($file != '.' && $file != '..') {
                    $this->mergeConfigFrom($folder.$file, strtolower($module).'.'.str_replace('.php', '', $file));
                }
            }
        }

        if(file_exists($path.'/'.$module.'/database/migrations'))  {
            $this->loadMigrationsFrom($path.'/'.$module.'/database/migrations');
        }

        // if unpublished ?!!
        if(file_exists($path.'/'.$module.'/resources/lang')) {
            $this->loadTranslationsFrom($path.'/'.$module.'/resources/lang', strtolower($module));
        }

        // if unpublished ?!!
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
        // what if user publish config and use that one ???
        $status = config('test0.aliases', 'unpublished');
        if(is_string($status)) {
            $path = base_path(config('modular.path')).'/'.$module.'/config/'.strtolower($module).'.php';
            if(file_exists($path)) {
                $config = require_once $path;
                if(isset($config['aliases'])) {
                    foreach ($config['aliases'] as $abstract => $concrete) {
                        $this->app->alias($abstract, $concrete);
                    }
                }
            }
        } else {
            if(!is_array($status)) { return; }
            foreach ($status as $abstract => $concrete) {
                $this->app->alias($abstract, $concrete);
            }
        }

    }

    /**
     * Register modules service providers.
     *
     * @param array $providers
     * @return void
     */
    protected function registerProviders($providers)
    {
        foreach ($providers as $provider) {
            $this->app->register($provider, true);
        }
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
        $kernel = new $kernel;
        $this->registerGroupsMiddleware($kernel);
        $this->registerRoutesMiddleware($kernel);
    }

    /** */
    protected function registerModules()
    {
        $path = base_path(config('modular.path'));
        if(!file_exists($path)) {
            mkdir($path);
        }
        // extract modules folders
        $modules = (function() use ($path) {
            foreach (scandir($path) as $module) {
                if($module != '.' && $module != '..') {
                    yield $module;
                }
            }
        })();

        foreach ($modules as $module) {
            $this->registerProviders((function() use ($module, $path) {
                $path = $path.'/'.$module.'/'.'Providers';
                if(!file_exists($path)) {
                    return [];
                }
                if(!is_dir($path)) {
                    return [];
                }
                $providers = (function() use ($module, $path) {
                    foreach (scandir($path) as $provider) {
                        if($provider != '.' && $provider != '..') {
                            $provider = config('modular.namespace').$module.'\\Providers\\'.str_replace('.php', '', $provider);
                            yield $provider;
                        }
                    }
                })();

                return $providers;
            })());

            $this->registerAlias($module);
            $this->configureModule($module);
            $this->loadKernel($module);
        }
    }

    /**
     * Register modules global middlewares.
     *
     * @return void
     */
    protected function registerMiddleware()
    {
        $path = base_path(config('modular.path'));
        // extract kernels
        $kernels = (function() use ($path) {
            foreach (scandir($path) as $module) {
                if($module != '.' && $module != '..') {
                    $kernel = config('modular.namespace').$module.'\\Kernel';
                    yield new $kernel;
                }
            }
        })();
        foreach ($kernels as $kernel) {
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

    /** @return bool */
    protected function active()
    {
        return config('modular.active', false);
    }

    public function __construct($app)
    {
        Autoloader::register();
        Autoloader::addNamespace(
            config('modular.namespace'),
            app()->basePath().'/'.config('modular.path')
        );
        parent::__construct($app);
    }

}
