<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient;

interface JsonApiRequestSenderInterface
{
    public const ERROR_BAD_RESPONSE = 'Bad response';
    public const ERROR_CONNECTION = 'Could not connect';
    public const ERROR_JSON_DECODE = 'Could not decode JSON';
    public const ERROR_UNHANDLED = 'Unhandled error';
    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    /**
     * @throws JsonApiRequestExceptionInterface
     */
    public function get(string $url, array $queryStrings = [], array $headers = []): array;

    /**
     * @throws JsonApiRequestExceptionInterface
     */
    public function post(string $url, array $queryStrings = [], array $headers = [], ?string $body = null): array;

    /**
     * @throws JsonApiRequestExceptionInterface
     */
    public function postData(string $url, array $queryStrings = [], array $headers = [], array $bodyData = []): array;
}
