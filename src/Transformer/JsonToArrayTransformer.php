<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonException;
use JsonException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use function json_decode;

use const JSON_THROW_ON_ERROR;

final class JsonToArrayTransformer implements JsonToArrayTransformerInterface
{
    public function transform(string $rawData, RequestInterface $request, ResponseInterface $response): array
    {
        try {
            $array = json_decode($rawData, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new ParseJsonException($request, $response, $exception);
        }

        return $array;
    }
}
