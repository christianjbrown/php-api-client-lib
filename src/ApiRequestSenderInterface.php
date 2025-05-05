<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Exception\Request\ConnectExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\BadResponseExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsExceptionInterface;

interface ApiRequestSenderInterface
{
    public const string METHOD_GET = 'GET';
    public const string METHOD_POST = 'POST';

    /**
     * @throws ConnectExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     */
    public function get(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): string;

    /**
     * @throws ConnectExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     */
    public function post(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?string $requestBody = null): string;

    /**
     * @throws ConnectExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     */
    public function postForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): string;
}
