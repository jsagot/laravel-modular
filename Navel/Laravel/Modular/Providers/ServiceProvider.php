<?php

namespace Navel\Laravel\Modular\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/** */
abstract class ServiceProvider extends BaseServiceProvider
{
    /**
     * The module name.
     *
     * @var string
     */
    protected $module = 'Module';

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
    abstract public function boot();

    /**
     * Register any application services.
     *
     * @return void
     */
    abstract public function register();

    public function __construct(Application $app)
    {
        $this->namespace = 'Modules\\'.$this->module.'\\';
        parent::__construct($app);
    }
}
