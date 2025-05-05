<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Request;

interface ConnectExceptionInterface extends RequestExceptionInterface
{
    public const string MESSAGE = 'Could not connect to %s';
}
