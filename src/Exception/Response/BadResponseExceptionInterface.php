<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

interface BadResponseExceptionInterface extends ResponseExceptionInterface
{
    public const string MESSAGE = 'Connected to %s but received a non-successful response (HTTP Code %d).';
}
