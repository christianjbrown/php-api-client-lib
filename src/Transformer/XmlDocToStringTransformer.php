<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use DOMDocument;

final class XmlDocToStringTransformer implements XmlDocToStringTransformerInterface
{
    /**
     * @param DOMDocument           $doc                 The document to serialize
     * @param string                $method              The HTTP method used for the request
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     */
    public function transform(DOMDocument $doc, string $method, string $requestUrl, array $requestQueryStrings = []): string
    {
        // saveXML() only returns false for an unbuildable document, which cannot occur for an
        // already-parsed DOMDocument, so the string cast is always exercised.
        return (string) $doc->saveXML();
    }
}
