<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use DOMDocument;

interface XmlDocToStringTransformerInterface
{
    /**
     * @param DOMDocument           $doc                 The document to serialize
     * @param string                $method              The HTTP method used for the request
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     */
    public function transform(DOMDocument $doc, string $method, string $requestUrl, array $requestQueryStrings = []): string;
}
