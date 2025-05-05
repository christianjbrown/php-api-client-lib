# API Client

This is library provides a simple request client for JSON and XML APIs. It is a wrapper around GuzzleHttp's `Client` class, that XML and JSON decodes responses back to an associative array, and provides common non-Guzzle specific exception classes to make it easier to catch and handle.



## :heavy_check_mark: Prerequisites

- [Git](https://git-scm.com/)
- [PHP](https://www.php.net/) 8.3 or higher (8.x)
- [Composer](https://getcomposer.org/)

:bulb: If you're on MacOS and have [Homebrew](https://brew.sh/), PHP and Composer will install with `brew install composer`.



# :building_construction: Installation

For your composer-enabled project:

```bash
composer require christianjbrown/php-api-client-lib
```



# :computer: Usage



## Setup



```php
use ChristianBrown\ApiClient\ApiClient;

$apiClient = new ApiClient();
$jsonApiRequestSender = $apiClient->getJsonApiRequestSender();

// or, for XML use
// $xmlApiRequestSender = $apiClient->getXmlApiRequestSender();
```



## `POST` examples



If you need to `POST` an API endpoint, use `post` like -

```php
use ChristianBrown\ApiClient\Exception\ExceptionInterface;

try {
    $data = $jsonApiRequestSender->post('url', ['query-string-1-key' => 'query-string-1-value'], [], ['body-key-1' => 'body-value-1']);
} catch (ExceptionInterface $e) {
    print $e->getMessage();
}
```



## `GET` example



If you need to `GET` data from an API endpoint, use `get` like -

```php
use ChristianBrown\ApiClient\Exception\ExceptionInterface;

try {
    $data = $jsonApiRequestSender->get('url', ['query-string-1-key' => 'query-string-1-value'], []);
} catch (ExceptionInterface $e) {
    print $e->getMessage();
}
```



