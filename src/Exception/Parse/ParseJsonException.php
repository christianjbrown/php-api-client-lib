<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use JsonException;

final class ParseJsonException extends AbstractParseException implements ParseJsonExceptionInterface
{
    private JsonException $jsonException;

    public function __construct(JsonException $jsonException, string $method, string $requestUrl, ?array $requestQueryStrings = [])
    {
        $this->jsonException = $jsonException;
        $message = sprintf(self::MESSAGE_SPRINTF, $method, $requestUrl, $jsonException->getMessage());
        parent::__construct($message, $method, $requestUrl, $requestQueryStrings, $jsonException);
    }

    public function getJsonException(): JsonException
    {
        return $this->jsonException;
    }
}
