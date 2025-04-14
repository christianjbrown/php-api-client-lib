<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use JsonException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class ParseJsonException extends AbstractParseException implements ParseJsonExceptionInterface
{
    private JsonException $jsonException;

    public function __construct(RequestInterface $request, ResponseInterface $response, JsonException $jsonException)
    {
        $message = sprintf(self::MESSAGE, $jsonException->getMessage());
        parent::__construct($request, $response, $message, $jsonException);
        $this->jsonException = $jsonException;
    }

    public function getJsonException(): JsonException
    {
        return $this->jsonException;
    }
}
