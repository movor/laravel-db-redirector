# Manage HTTP redirections in Laravel using database

[![Build](https://travis-ci.org/movor/laravel-db-redirector.svg?branch=master)](https://travis-ci.org/movor/laravel-db-redirector)
[![Downloads](https://poser.pugx.org/movor/laravel-db-redirector/downloads)](https://packagist.org/packages/movor/laravel-db-redirector)
[![Stable](https://poser.pugx.org/movor/laravel-db-redirector/v/stable)](https://packagist.org/packages/movor/laravel-db-redirector)
[![License](https://poser.pugx.org/movor/laravel-db-redirector/license)](https://packagist.org/packages/movor/laravel-db-redirector)

***

## Compatibility

The package is compatible with Laravel versions 5.5.* and 5.6.*

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

Database redirector middleware needs to be added to middleware array:

```php
// File: app/Http/Kernel.php

// ...

protected $middleware = [
    // ...
    \Movor\LaravelDbRedirector\Http\Middleware\DbRedirectorMiddleware::class
];
```

Run migrations to create table which will store redirect rules:

```bash
php artisan migrate
```

## Usage

Creating a redirect is easy. You just have to add db record via provided RedirectRule model.
Default status code for redirections will be 301 (Moved Permanently).

```php
use Movor\LaravelDbRedirector\Models\RedirectRule;

// ...

RedirectRule::create([
    'origin' => 'one/two',
    'destination' => 'three'
]);
```

You can also specify another redirection status code:

```php
RedirectRule::create([
    'origin' => 'one/two',
    'destination' => 'three',
    'status_code' => 307 // Temporary Redirect
]);
```

You may use route parameters just like in native Laravel routes,
they'll be passed down the road - from origin to destination:

```php
RedirectRule::create([
    'origin' => 'one/{param}',
    'destination' => 'two/{param}'
]);

// If we visit: "/one/foo" we will end up at "two/foo"
```

Optional parameters can be used as a wildcards:

```php
RedirectRule::create([
    'origin' => 'one/{wildcard1?}/{wildcard?}',
    'destination' => 'four'
]);

// If we visit: "/one" or "/one/two" or "/one/two/three"
// we will end up at "/four"
```

Chained redirects will also work:

```php
RedirectRule::create([
    'origin' => 'one',
    'destination' => 'two'
]);

RedirectRule::create([
    'origin' => 'two',
    'destination' => 'three'
]);

RedirectRule::create([
    'origin' => 'three',
    'destination' => 'four'
]);

// If we visit: "/one" we'll end up at "/four"
```

We also can delete the whole chain at once
(3 previous redirect records in this example):

```php
RedirectRule::deleteChainedRecursively('/ten');
```

Sometimes it's possible that you'll have more than one redirection with
the same destination. So it's smart to surround code with the try catch
block, because exception will be raised in this case:

```php
RedirectRule::create(['origin' => 'one/two', 'destination' => 'three/four']);
RedirectRule::create(['origin' => 'three/four', 'destination' => 'five/six']);

// One more with same destination ("five/six") as the previous one.
RedirectRule::create(['origin' => 'ten/eleven', 'destination' => 'five/six']);

try {
    RedirectRule::deleteChainedRecursively('five/six');
} catch (\Exception $e) {
    // ... handle exception
}
```