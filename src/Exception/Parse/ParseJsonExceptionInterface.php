<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use JsonException;

interface ParseJsonExceptionInterface extends ParseExceptionInterface
{
    public const string MESSAGE = 'JSON decoding error: %s';

    public function getJsonException(): JsonException;
}
