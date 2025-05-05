<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Request;

use ChristianBrown\ApiClient\Exception\ExceptionInterface;
use Psr\Http\Message\RequestInterface;

interface RequestExceptionInterface extends ExceptionInterface
{
    public function getRequest(): RequestInterface;
}
