# laravel-modular
Make your Laravel 5.7 application modular.

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

### Add autoloader to the top of bootstrap/app.php file.

```php
<?php

/* Modules Autoloader */
 Navel\Laravel\Autoloader::register();
 Navel\Laravel\Autoloader::addNamespace('Modules', __DIR__.'/../modules');
 
 $app = new Illuminate\Foundation\Application(
    dirname(__DIR__)
);

...

```

### Then create a fresh new module with this command:

```bash
$ php artisan module:make your_module_name
```

your_module_name should be as simple as possible (DO NOT USE "-_." or any special character. CamelCase works).

### You can also create a demo module :


```bash
$ php artisan modular:demo
```
## Enjoy ;-)
