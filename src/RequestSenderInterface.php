<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient;

interface RequestSenderInterface
{
    public function get(string $friendlyName, string $url, array $queryStrings = [], array $headers = []): array;

    public function post(string $friendlyName, string $url, array $queryStrings = [], array $headers = [], ?string $body = null): array;

    public function postData(string $friendlyName, string $url, array $queryStrings = [], array $headers = [], array $bodyData = []): array;
}
