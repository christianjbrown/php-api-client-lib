<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlExceptionInterface;
use DOMDocument;

use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;

final class StringToXmlDocTransformer implements StringToXmlDocTransformerInterface
{
    /**
     * @throws ParseXmlExceptionInterface
     */
    public function transform(string $string, string $method, string $requestUrl, array $requestQueryStrings = []): DOMDocument
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $success = $doc->loadXML($string);
        if (!$success) {
            $errors = libxml_get_errors();
            libxml_clear_errors();

            throw new ParseXmlException($errors, $method, $requestUrl, $requestQueryStrings);
        }

        return $doc;
    }
}
