<?php

namespace Navel\Laravel\Modular;

/**
 *
 */
class Kernel
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
    protected $routeMiddleware = [];

    /**
     *
     */
    protected function middleware()
    {
        return $this->middleware;
    }

    /**
     *
     */
    protected function middlewareGroups()
    {
        return $this->middlewareGroups;
    }

    /**
     *
     */
    protected function routeMiddleware()
    {
        return $this->routeMiddleware;
    }

    /**
     * @param string $key
     * @return array|null
     */
    public function __get($key)
    {
        if(method_exists($this, $key)) {
            return $this->$key();
        }
        return null;
    }

}
