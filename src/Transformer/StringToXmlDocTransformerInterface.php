<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseXmlExceptionInterface;
use DOMDocument;

interface StringToXmlDocTransformerInterface
{
    /**
     * @param string                $string              The XML string to parse
     * @param string                $method              The HTTP method used for the request
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     *
     * @throws ParseXmlExceptionInterface
     */
    public function transform(string $string, string $method, string $requestUrl, array $requestQueryStrings = []): DOMDocument;
}
