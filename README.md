[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/tonis-io/response-cache/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/response-cache/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/tonis-io/response-cache/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/response-cache/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/tonis-io/response-cache/badges/build.png?b=master)](https://scrutinizer-ci.com/g/tonis-io/response-cache/build-status/master)

# Tonis\ResponseCache

Tonis\ResponseCache is simple middleware caches requests based on path/method and return static cache if a hit is 
found.

Composer
--------

```
composer require tonis-io/response-cache
```

Usage
-----

```php
// create an instance and give it your psr-3 logger
$responseCache = new \Tonis\ResponseCache;

// add $responseCache to your middleware queue
```
