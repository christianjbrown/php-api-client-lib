<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Throwable;

final class JsonApiRequestException extends RuntimeException implements JsonApiRequestExceptionInterface
{
    private RequestInterface $request;
    private ?ResponseInterface $response;

    public function __construct(RequestInterface $request, ?ResponseInterface $response = null, string $message = '', ?Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
        $this->response = $response;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }
}
