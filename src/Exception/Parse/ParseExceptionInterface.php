<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use ChristianBrown\ApiClient\Exception\ExceptionInterface;
use Psr\Http\Message\ResponseInterface;

interface ParseExceptionInterface extends ExceptionInterface
{
    public function getResponse(): ResponseInterface;
}
