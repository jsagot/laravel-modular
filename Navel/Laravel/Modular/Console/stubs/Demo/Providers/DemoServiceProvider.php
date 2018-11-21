<?php

namespace Modules\Demo\Providers;

use Illuminate\Support\Facades\App;
use Modules\Demo\Repository\DummyRepository;
use Navel\Laravel\Modular\Providers\ServiceProvider;

class DemoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*
        // publish config (allready done by ServiceProvider base class)
        $this->publishes([
            __DIR__.'/../config/demo.php' => config_path('demo.php'),
        ], 'module-blog-config');

        // load migrations (allready done by ServiceProvider base class)
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // load translations (allready done by ServiceProvider base class)
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'demo');

        // publish translations for user override
        $this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/demo'),
        ], 'module.demo.lang');

        // load views (allready done by ServiceProvider base class)
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'demo');
        */
        
        // publish views for user override
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/demo'),
        ], 'module.demo.views');

        // load routes if you don't use a RouteServiceProvider
        //$this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // publish assets
        /*
        $this->publishes([
            __DIR__.'/path/to/assets' => public_path('vendor/demo'),
        ], 'module.demo.assets'); // */

        /* Artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                FooCommand::class,
                BarCommand::class,
            ]);
        }
        // */
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // ex: binding facade on alias
        App::bind('dummy', function() {
            return new DummyRepository;
        });

        //
    }
}
