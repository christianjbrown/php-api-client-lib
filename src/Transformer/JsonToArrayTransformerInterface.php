<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;

interface JsonToArrayTransformerInterface
{
    public const string MESSAGE_NON_ARRAY_SPRINTF = 'Decoded JSON is not an array whilst %sing to %s';

    /**
     * @param string                $data                The JSON string to decode
     * @param string                $method              The HTTP method used for the request
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     *
     * @throws ParseJsonExceptionInterface
     *
     * @return array<array-key, mixed>
     */
    public function transform(string $data, string $method, string $requestUrl, array $requestQueryStrings = []): array;
}
