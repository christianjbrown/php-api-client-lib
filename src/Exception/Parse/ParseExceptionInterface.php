<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use ChristianBrown\ApiClient\Exception\ExceptionInterface;

interface ParseExceptionInterface extends ExceptionInterface
{
    public function getMethod(): string;

    public function getQueryStrings(): ?array;

    public function getUrl(): string;
}
