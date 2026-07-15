<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonException;
use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;
use JsonException;

use function is_array;
use function json_decode;
use function sprintf;

use const JSON_THROW_ON_ERROR;

final class JsonToArrayTransformer implements JsonToArrayTransformerInterface
{
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
    public function transform(string $data, string $method, string $requestUrl, array $requestQueryStrings = []): array
    {
        try {
            $array = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ParseJsonException($exception, $method, $requestUrl, $requestQueryStrings);
        }

        if (!is_array($array)) {
            throw new ParseJsonException(new JsonException(sprintf(self::MESSAGE_NON_ARRAY_SPRINTF, $method, $requestUrl)), $method, $requestUrl, $requestQueryStrings);
        }

        return $array;
    }
}
