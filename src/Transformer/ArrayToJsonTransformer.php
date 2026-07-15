<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonException;
use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;
use JsonException;

use function json_encode;

use const JSON_THROW_ON_ERROR;

final class ArrayToJsonTransformer implements ArrayToJsonTransformerInterface
{
    /**
     * @param array<array-key, mixed> $data
     * @param string                  $method              The HTTP method used for the request
     * @param string                  $requestUrl          The request URL
     * @param array<string, string>   $requestQueryStrings
     *
     * @throws ParseJsonExceptionInterface
     */
    public function transform(array $data, string $method, string $requestUrl, array $requestQueryStrings = []): string
    {
        try {
            $string = json_encode($data, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ParseJsonException($exception, $method, $requestUrl, $requestQueryStrings);
        }

        return $string;
    }
}
