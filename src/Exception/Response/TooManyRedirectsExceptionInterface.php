<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

interface TooManyRedirectsExceptionInterface extends ResponseExceptionInterface
{
    public const string MESSAGE = 'Connected to %s but received too many redirects.';
}
