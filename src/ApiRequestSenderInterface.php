<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use ChristianBrown\ApiClient\Exception\Request\ConnectException;
use ChristianBrown\ApiClient\Exception\Response\ResponseException;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsException;

interface ApiRequestSenderInterface
{
    public const string METHOD_GET = 'GET';
    public const string METHOD_POST = 'POST';
    public const string METHOD_PUT = 'PUT';

    /**
     * @throws ConnectException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    public function get(string $url, array $queryStrings = [], array $headers = []): array;

    /**
     * @throws ConnectException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    public function post(string $url, array $queryStrings = [], array $headers = [], ?string $body = null): array;

    /**
     * @throws ConnectException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    public function postData(string $url, array $queryStrings = [], array $headers = [], array $bodyData = []): array;
}
