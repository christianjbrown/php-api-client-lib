<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use DOMDocument;

final class XmlDocToStringTransformer implements XmlDocToStringTransformerInterface
{
    public function transform(DOMDocument $doc, string $method, string $requestUrl, array $requestQueryStrings = []): string
    {
        return $doc->saveXML();
    }
}
