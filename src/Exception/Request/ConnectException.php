<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Request;

use ChristianBrown\ApiClient\Exception\AbstractException;
use Psr\Http\Message\RequestInterface;
use Throwable;

final class ConnectException extends AbstractException implements ConnectExceptionInterface
{
    public function __construct(RequestInterface $request, ?Throwable $previous = null)
    {
        $message = sprintf(self::MESSAGE, $request->getUri()->__toString());
        parent::__construct($request, $message, $previous);
    }
}
