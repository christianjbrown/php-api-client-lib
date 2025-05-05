<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use JsonException;

interface ParseJsonExceptionInterface extends ParseExceptionInterface
{
    public const string MESSAGE_SPRINTF = "JSON decoding error(s) whilst %sing to %s:\n%s";

    public function getJsonException(): JsonException;
}
