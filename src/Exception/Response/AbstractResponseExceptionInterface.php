<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

use ChristianBrown\ApiClient\Exception\ExceptionInterface;
use Psr\Http\Message\ResponseInterface;

interface AbstractResponseExceptionInterface extends ExceptionInterface
{
    public function getResponse(): ResponseInterface;
}
