# Laravel-Modular
Make your Laravel 5.7 application modular.

## About

Modular creates and manages modules for Laravel 5.7 only (for now). The created modules behave like any package designed for Laravel. With few benefits:

 + Autoloaded modules
 + Auto registration of middlewares, langs, views and routes (web only)
 + Auto-merged configuration file
 
## Installation
Create a new Laravel 5.7 project.

```bash
$ composer create-project --prefer-dist laravel/laravel your_project
```

Install the latest version with

```bash
$ composer require jsagot/laravel-modular
```

## Basic Usage

### First add ModularServiceProvider to config/app.php

```php
...

/*
 * Package Service Providers...
 */
 Navel\Laravel\Modular\Providers\ModularServiceProvider::class,
 
 ...
```

### Publish configuration file:

```bash
$ php artisan vendor:publish --provider="Navel\Laravel\Modular\Providers\ModularServiceProvider" --tag="modular.config"
```

### Activate Laravel-Modular

in config/modular.php

```php
return [
    'active' => true,
    'path' => 'modules',
    'namespace' => 'Modules\\',
];
```


### Create 'modules/' directory

Modules directory should be at the root of your Laravel project.

ex:

+ your_project/
  + app/
  + bootstrap/
  + ...
  + modules/

### Then create a fresh new module :

```bash
$ php artisan module:make your_module_name
```

The default option creates modules in the 'modules/' directory. This will be customizable in future version.

your_module_name should be as simple as possible (DO NOT USE "-_." or any special character. CamelCase works).

### You can also create a demo module :


```bash
$ php artisan modular:demo
```

You can then access to http://localhost/demo?demo=demo to see the demo module in action. (See code and comments)

## Enjoy ;-)
