<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception;

use Psr\Http\Message\RequestInterface;
use RuntimeException;
use Throwable;

abstract class AbstractException extends RuntimeException implements ExceptionInterface
{
    private RequestInterface $request;

    public function __construct(RequestInterface $request, string $message = '', ?Throwable $previous = null, int $code = 0)
    {
        parent::__construct($message, $code, $previous);
        $this->request = $request;
    }

    final public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
