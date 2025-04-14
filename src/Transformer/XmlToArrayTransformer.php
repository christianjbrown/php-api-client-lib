<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use function json_encode;
use function libxml_clear_errors;
use function libxml_get_errors;
use function libxml_use_internal_errors;
use function simplexml_load_string;

final class XmlToArrayTransformer implements XmlToArrayTransformerInterface
{
    private JsonToArrayTransformerInterface $jsonToArrayTransformer;

    public function __construct(JsonToArrayTransformerInterface $jsonToArrayTransformer)
    {
        $this->jsonToArrayTransformer = $jsonToArrayTransformer;
    }

    public function transform(string $rawData, RequestInterface $request, ResponseInterface $response): array
    {
        libxml_use_internal_errors(true);
        $simpleXml = simplexml_load_string($rawData);
        if (false === $simpleXml) {
            $errors = libxml_get_errors();
            libxml_clear_errors();

            throw new ParseXmlException($request, $response, $errors);
        }
        $jsonData = json_encode((array) $simpleXml);

        $array = $this->jsonToArrayTransformer->transform($jsonData, $request, $response);

        return $array;
    }
}
