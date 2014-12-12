Avatar Component for Orchestra Platform
==============

Social Authentication Extension for Orchestra Platform

[![Latest Stable Version](https://img.shields.io/github/release/orchestral/oneauth.svg?style=flat)](https://packagist.org/packages/orchestra/oneauth)
[![Total Downloads](https://img.shields.io/packagist/dt/orchestra/oneauth.svg?style=flat)](https://packagist.org/packages/orchestra/oneauth)
[![MIT License](https://img.shields.io/packagist/l/orchestra/oneauth.svg?style=flat)](https://packagist.org/packages/orchestra/oneauth)
[![Build Status](https://img.shields.io/travis/orchestral/oneauth/master.svg?style=flat)](https://travis-ci.org/orchestral/oneauth)
[![Coverage Status](https://img.shields.io/coveralls/orchestral/oneauth/master.svg?style=flat)](https://coveralls.io/r/orchestral/oneauth?branch=master)
[![Scrutinizer Quality Score](https://img.shields.io/scrutinizer/g/orchestral/oneauth/master.svg?style=flat)](https://scrutinizer-ci.com/g/orchestral/oneauth/)

## Table of Content

* [Version Compatibility](#compatibility)
* [Installation](#installation)
* [Usage](#usage)

## Version Compatibility

Laravel  | OneAuth
:--------|:---------
 5.0.x   | 3.0.x@dev

## Installation

To install through composer, simply put the following in your `composer.json` file:

```json
{
	"require": {
		"orchestra/oneauth": "3.0.*"
	}
}
```

And then run `composer install` to fetch the package.

### Quick Installation

You could also simplify the above code by using the following command:

```
composer require "orchestra/oneauth=3.0.*"
```

## Usage

In `app/Http/routes.php`:

```php
<?php
Route::get('social/{provider}/connect', [
    'uses'  => 'Auth\SocialController@connect'
])->where('{provider}', '(.+)');
```

In `app/Http/Controllers/Auth/SocialController.php`:

```php
<?php namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Guard;
use App\Http\Controllers\Controller;
use Orchestra\OneAuth\Contracts\Listener\ConnectUser;
use Orchestra\OneAuth\Processor\AuthenticateUser as Processor;

class SocialController extends Controller implements ConnectUser
{
    /**
     * Connect with social provider.
     *
     * @param  \Orchestra\OneAuth\Processor\AuthenticateUser  $processor
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $provider
     * @return mixed
     */
    public function connect(Processor $processor, Request $request, $provider = 'facebook')
    {
        return $processor->execute($this, $provider, $request->has('code'));
    }

    /**
     * Response when user has connected.
     *
     * @param  array  $data
     * @param  \Illuminate\Contracts\Auth\Guard  $auth
     * @return mixed
     */
    public function userHasConnected(array $data, Guard $auth)
    {
        return redirect(handles('app::/'));
    }
}
```
