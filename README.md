# Manage HTTP redirections using database

[![Build](https://travis-ci.org/movor/laravel-db-redirector.svg?branch=master)](https://travis-ci.org/movor/laravel-db-redirector)
[![Downloads](https://poser.pugx.org/movor/laravel-db-redirector/downloads)](https://packagist.org/packages/movor/laravel-db-redirector)
[![Stable](https://poser.pugx.org/movor/laravel-db-redirector/v/stable)](https://packagist.org/packages/movor/laravel-db-redirector)
[![License](https://poser.pugx.org/movor/laravel-db-redirector/license)](https://packagist.org/packages/movor/laravel-db-redirector)

## Installation

Install the package via composer:

```bash
composer require movor/laravel-db-redirector
```

The package needs to be registered in service providers:

```php
// File: config/app.php

// ...

/*
 * Package Service Providers...
 */

// ...

Movor\LaravelDbRedirector\Providers\DbRedirectorServiceProvider::class,
```

Run migrations to create table which will hold out redirect rules:

```bash
php artisan migrate
```

## Usage

Creating a redirect is easy. You just have to add db record via provided RedirectRule model.
Default status code for redirections will be 301 (Moved Permanently).

```php
use Movor\LaravelDbRedirector\Models\RedirectRule;

\\ ...

RedirectRule::create([
    'origin' => 'foo/bar',
    'destination' => 'baz'
]);
```

You can also specify another redirection status code:

```php
use Movor\LaravelDbRedirector\Models\RedirectRule;

\\ ...

RedirectRule::create([
    'origin' => 'foo/bar',
    'destination' => 'baz',
    'status_code' => 307 // Temporary Redirect
]);
```

You may use route parameters just like in native Laravel routes:

```php
RedirectRule::create([
    'origin' => 'foo/{param}',
    'destination' => 'bar/{param}'
]);
```

Optional parameters are also supported:

```php
RedirectRule::create([
    'origin' => 'foo/{param?}',
    'destination' => 'bar/{param?}'
]);
```