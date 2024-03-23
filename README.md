# Overview

This is library provides a simple request client for JSON APIs. It is a wrapper around GuzzleHttp's `Client` class, that JSON decodes responses and provides a common exception to make it easier to catch and handle.



## :heavy_check_mark: Prerequisites

- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) 8.2 or higher (8.x)
- [Composer](https://getcomposer.org/)

:bulb: If you're on MacOS and have [Homebrew](https://brew.sh/), PHP and Composer will install with `brew install composer`.



# :building_construction: Installation

For your composer-enabled project:

```bash
composer require christianjbrown/php-json-api-client-lib
```



# :computer: Usage



## Setup

```php
use ChristianBrown\JsonApiClient\JsonApiRequestSender;
use GuzzleHttp\Client;

$guzzleClient = new Client();
$jsonApiRequestSender = new JsonApiRequestSender($guzzleClient);
```

Dependency injection be used to reduce the lines of code above.



## Post examples

If you need to `POST` JSON data to a JSON API endpoint, use `postData` like -

```php
use ChristianBrown\JsonApiClient\JsonApiRequestExceptionInterface;

try {
    $data = $jsonApiRequestSender->postData('url', ['query-string-1-key' => 'query-string-1-value'], [], ['body-key-1' => 'body-value-1']);
} catch (JsonApiRequestExceptionInterface $e) {
    print $e->getMessage();
}
```

If you need to `POST` key-value pair data to a JSON API endpoint, use `post` like -

```php
use ChristianBrown\JsonApiClient\JsonApiRequestExceptionInterface;

try {
    $data = $jsonApiRequestSender->postData('url', ['query-string-1-key' => 'query-string-1-value'], [], 'body-key-1=body-value-1');
} catch (JsonApiRequestExceptionInterface $e) {
    print $e->getMessage();
}
```



## Get example

If you need to `GET` a JSON API endpoint, use `get` like -

```php
use ChristianBrown\JsonApiClient\JsonApiRequestExceptionInterface;

try {
    $data = $jsonApiRequestSender->get('url', ['query-string-1-key' => 'query-string-1-value'], []);
} catch (JsonApiRequestExceptionInterface $e) {
    print $e->getMessage();
}
```

