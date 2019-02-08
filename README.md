# AuraSessionMiddleware

**Middleware to inject [Aura.Session Segments](https://github.com/auraphp/Aura.Session) into [PSR-7 ServerRequests](http://www.php-fig.org/psr/psr-7/#3-2-psr-http-message-requestinterface).**

[![Packagist](https://img.shields.io/packagist/v/germania-kg/aurasession-middleware.svg?style=flat)](https://packagist.org/packages/germania-kg/aurasession-middleware)
[![PHP version](https://img.shields.io/packagist/php-v/germania-kg/aurasession-middleware.svg)](https://packagist.org/packages/germania-kg/aurasession-middleware)
[![Build Status](https://img.shields.io/travis/GermaniaKG/AuraSessionMiddleware.svg?label=Travis%20CI)](https://travis-ci.org/GermaniaKG/AuraSessionMiddleware)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/badges/build.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/build-status/master)


## Installation with Composer

```bash
$ composer require germania-kg/aurasession-middleware
```

## Usage

```php
<?php
use Germania\AuraSessionMiddleware\AuraSessionSegmentMiddleware;

// Create session and segment, cf. Aura.Session docs
$session_factory = new \Aura\Session\SessionFactory;
$session = $session_factory->newInstance($_COOKIE);
$segment = $session->getSegment('Vendor\Package\ClassName');

// Optional with PSR-3 Logger
$mw = new AuraSessionSegmentMiddleware( $segment );
$mw = new AuraSessionSegmentMiddleware( $segment, $logger );
```


### Inside your routes
```php
<?php
$app = new \Slim\App();
$app->get('/books/{id}', function ($request, $response, $args) {
	// This is your Aura.Session segment
    $session = $request->getAttribute("session");
	...    
});
```

### Set PSR7 Request attribute name

Per default, the SessionSegment is stored as the PSR7 Request attribute named `session`.
You can set a custom name right after instantiation, i.e. before the middleware is invoked:

```php
<?php
$mw = new AuraSessionSegmentMiddleware( $segment );

// "session" per default
echo $mv->getRequestAttributeName( );

// Choose another one...
$mv->setRequestAttributeName( "custom_name" );

// Inside route:
$session = $request->getAttribute("custom_name");
```


## Pimple Service Provider

```php
<?php
use Germania\AuraSessionMiddleware\PimpleServiceProvider;
use Aura\Session\SegmentInterface;
use Psr\Log\LoggerInterface;

// have your Pimple DIC ready, and optionally a PSR3 Logger:
$sp = new PimpleServiceProvider("session", "request_attr");
$sp = new PimpleServiceProvider("session", "request_attr", $psr3_logger);
$sp->register( $dic );

// Grab your services
$yes = $dic['Session'] instanceOf SegmentInterface;
$yes = $dic['Session.Logger'] instanceOf LoggerInterface;
$yes = is_callable( $dic['Session.Middleware'] );
```

### Use with Slim Framework:

```php
<?php
$app = new \Slim\App;
$app->add( $dic['Session.Middleware'] );
```



## Development

```bash
$ git clone https://github.com/GermaniaKG/AuraSessionMiddleware.git
$ cd AuraSessionMiddleware
$ composer install
```

## Unit tests

Either copy `phpunit.xml.dist` to `phpunit.xml` and adapt to your needs, or leave as is. Run [PhpUnit](https://phpunit.de/) test or composer scripts like this:

```bash
$ composer test
# or
$ vendor/bin/phpunit
```
