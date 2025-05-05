<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseXmlExceptionInterface;
use DOMDocument;

interface StringToXmlDocTransformerInterface
{
    /**
     * @throws ParseXmlExceptionInterface
     */
    public function transform(string $string, string $method, string $requestUrl, array $requestQueryStrings = []): DOMDocument;
}
