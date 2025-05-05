<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

use ChristianBrown\ApiClient\Exception\AbstractException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

abstract class AbstractResponseException extends AbstractException implements ResponseExceptionInterface
{
    private ResponseInterface $response;

    public function __construct(RequestInterface $request, ResponseInterface $response, string $message, ?Throwable $previous = null)
    {
        $code = $response->getStatusCode();
        parent::__construct($request, $message, $previous, $code);
        $this->response = $response;
    }

    final public function getResponse(): ResponseInterface
    {
        return $this->response;
    }
}
