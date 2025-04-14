<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception;

use Psr\Http\Message\RequestInterface;
use Throwable;

interface ExceptionInterface extends Throwable
{
    public function getRequest(): RequestInterface;
}
