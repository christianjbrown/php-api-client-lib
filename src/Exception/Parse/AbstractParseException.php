<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use ChristianBrown\ApiClient\Exception\AbstractException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

abstract class AbstractParseException extends AbstractException implements ParseExceptionInterface
{
    private ResponseInterface $response;

    public function __construct(RequestInterface $request, ResponseInterface $response, string $message, ?Throwable $previous = null)
    {
        parent::__construct($request, $message, $previous);
        $this->response = $response;
    }

    final public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
