<?php

namespace Navel\Laravel\Modular;

use Illuminate\Foundation\Application as BaseApplication;

/**
 *
 */
class Application extends BaseApplication
{
    /**
    * @param string $path
    * @return string
    */
    public function modulePath($path = '')
    {
        // /Modules/*
        return $this->basePath.DIRECTORY_SEPARATOR.'modules'.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}
