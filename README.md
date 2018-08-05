# Manage HTTP redirections using database

[![Latest Version on Packagist](https://img.shields.io/packagist/v/movor/laravel-db-redirector.svg?style=flat-square)](https://packagist.org/packages/movor/laravel-db-redirector)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/movor/laravel-db-redirector/master.svg?style=flat-square)](https://travis-ci.org/movor/laravel-db-redirector)
[![Total Downloads](https://img.shields.io/packagist/dt/movor/laravel-db-redirector.svg?style=flat-square)](https://packagist.org/packages/movor/laravel-db-redirector)

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

## Usage

Creating a databse redirect is easy. You just have to add db record via provided RedirectRule model.
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

You may use route parameters just like Laravel routes:

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