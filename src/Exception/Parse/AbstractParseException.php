<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use RuntimeException;
use Throwable;

abstract class AbstractParseException extends RuntimeException implements ParseExceptionInterface
{
    protected string $method;
    protected ?array $queryStrings = null;
    protected string $url;

    public function __construct(string $message, string $method, string $requestUrl, ?array $requestQueryStrings = [], ?Throwable $previous = null)
    {
        $this->method = $method;
        $this->url = $requestUrl;
        $this->queryStrings = $requestQueryStrings;

        parent::__construct($message, 0, $previous);
    }

    final public function getMethod(): string
    {
        return $this->method;
    }

    final public function getQueryStrings(): ?array
    {
        return $this->queryStrings;
    }

    final public function getUrl(): string
    {
        return $this->url;
    }
}
