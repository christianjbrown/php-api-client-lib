<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Request;

use ChristianBrown\ApiClient\Exception\ExceptionInterface;

interface ConnectExceptionInterface extends ExceptionInterface
{
    public const string MESSAGE = 'Could not connect to %s';
}
