#AuraSessionMiddleware

**Middleware to inject [Aura.Session Segments](https://github.com/auraphp/Aura.Session) into [PSR-7 ServerRequests](http://www.php-fig.org/psr/psr-7/#3-2-psr-http-message-requestinterface).**

[![Build Status](https://travis-ci.org/GermaniaKG/AuraSessionMiddleware.svg?branch=master)](https://travis-ci.org/GermaniaKG/AuraSessionMiddleware)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/GermaniaKG/AuraSessionMiddleware/?branch=master)



##Installation

```bash
$ composer require germania-kg/aurasession-middleware
```

##Usage

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

###Inside your routes
```php
<?php
$app = new \Slim\App();
$app->get('/books/{id}', function ($request, $response, $args) {
	// This is your Aura.Session segment
    $session = $request->getAttribute("session");
	...    
});
```


##Development and testing

Clone repo, use [Git Flow](https://github.com/nvie/gitflow). Work on *develop* branch.

```bash
# Clone Repo
$ git clone git@github.com:GermaniaKG/AuraSessionMiddleware.git aurasession-middleware
$ cd aurasession-middleware
$ composer install
```

For testing, copy PHPUnit configuration file and customize if needed.

```bash
$ cp phpunit.xml.dist phpunit.xml
$ phpunit
```
