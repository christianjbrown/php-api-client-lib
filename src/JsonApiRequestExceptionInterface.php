<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

interface JsonApiRequestExceptionInterface extends Throwable
{
    public function getRequest(): RequestInterface;

    public function getResponse(): ?ResponseInterface;
}
