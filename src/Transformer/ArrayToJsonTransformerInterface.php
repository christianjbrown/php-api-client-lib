<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;

interface ArrayToJsonTransformerInterface
{
    /**
     * @param array<array-key, mixed> $data
     * @param string                  $method              The HTTP method used for the request
     * @param string                  $requestUrl          The request URL
     * @param array<string, string>   $requestQueryStrings
     *
     * @throws ParseJsonExceptionInterface
     */
    public function transform(array $data, string $method, string $requestUrl, array $requestQueryStrings = []): string;
}
