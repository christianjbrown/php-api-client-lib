<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

use ChristianBrown\ApiClient\Exception\ExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface ResponseExceptionInterface extends ExceptionInterface
{
    public function getRequest(): RequestInterface;

    public function getResponse(): ResponseInterface;
}
