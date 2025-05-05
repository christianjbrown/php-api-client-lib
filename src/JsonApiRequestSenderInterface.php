<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;
use ChristianBrown\ApiClient\Exception\Request\ConnectExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\BadResponseExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsExceptionInterface;

interface JsonApiRequestSenderInterface
{
    /**
     * @throws ConnectExceptionInterface
     * @throws ParseJsonExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     */
    public function get(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): array;

    /**
     * @throws ConnectExceptionInterface
     * @throws ParseJsonExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     */
    public function post(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?array $requestBodyArray = null): array;

    /**
     * @throws ConnectExceptionInterface
     * @throws ParseJsonExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     */
    public function postForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): array;
}
