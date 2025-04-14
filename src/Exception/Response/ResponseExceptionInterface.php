<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

use Psr\Http\Message\ResponseInterface;

interface ResponseExceptionInterface extends AbstractResponseExceptionInterface
{
    public const string MESSAGE = 'Connected to %s but received a non-successful response (HTTP Code %d).';

    public function getResponse(): ResponseInterface;
}
