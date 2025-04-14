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
use ChristianBrown\ApiClient\ApiRequestSender;
use ChristianBrown\ApiClient\Model\ApiFormat;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformer;
use GuzzleHttp\Client;

$guzzleClient = new Client();
$jsonToArrayTransformer = new JsonToArrayTransformer();
$apiRequestSender = new ApiRequestSender($guzzleClient, $jsonToArrayTransformer);
```

Alternatively -

```php
use ChristianBrown\ApiClient\ApiClient;

$apiClient = new ApiClient();
$apiRequestSender = $apiClient->getApiSenderForJson();
```

XML is also supported.



## `POST` examples



If you need to `POST` array data as JSON or XML to a JSON or XML API endpoint, use `postData` like -

```php
use ChristianBrown\ApiClient\Exception\ExceptionInterface;

try {
    $data = $apiRequestSender->postData('url', ['query-string-1-key' => 'query-string-1-value'], [], ['body-key-1' => 'body-value-1']);
} catch (ExceptionInterface $e) {
    print $e->getMessage();
}
```



If you need to `POST` raw data to an API endpoint, use `post` like -

```php
use ChristianBrown\ApiClient\Exception\ExceptionInterface;

try {
    $data = $apiRequestSender->post('url', ['query-string-1-key' => 'query-string-1-value'], [], 'body-key-1=body-value-1');
} catch (ExceptionInterface $e) {
    print $e->getMessage();
}
```



## `GET` example



If you need to `GET` data from an API endpoint, use `get` like -

```php
use ChristianBrown\ApiClient\Exception\ExceptionInterface;

try {
    $data = $apiRequestSender->get('url', ['query-string-1-key' => 'query-string-1-value'], []);
} catch (ExceptionInterface $e) {
    print $e->getMessage();
}
```



