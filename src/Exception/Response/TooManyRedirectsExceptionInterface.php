<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

use Psr\Http\Message\ResponseInterface;

interface TooManyRedirectsExceptionInterface extends AbstractResponseExceptionInterface
{
    public const string MESSAGE = 'Connected to %s but received too many redirects.';

    public function getResponse(): ResponseInterface;
}
