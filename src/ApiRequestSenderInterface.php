<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Exception\Request\ConnectException;
use ChristianBrown\ApiClient\Exception\Response\BadResponseException;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsException;

interface ApiRequestSenderInterface
{
    public const string METHOD_GET = 'GET';
    public const string METHOD_POST = 'POST';

    /**
     * @throws ConnectException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    public function get(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): string;

    /**
     * @throws ConnectException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    public function post(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?string $requestBody = null): string;

    /**
     * @throws ConnectException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    public function postData(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyData = []): string;
}
