<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;
use ChristianBrown\ApiClient\Exception\Request\ConnectExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\BadResponseExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsExceptionInterface;
use ChristianBrown\ApiClient\Transformer\ArrayToJsonTransformerInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;

final class JsonApiRequestSender implements JsonApiRequestSenderInterface
{
    private ApiRequestSenderInterface $apiRequestSender;
    private ArrayToJsonTransformerInterface $requestTransformer;
    private JsonToArrayTransformerInterface $responseTransformer;

    public function __construct(ApiRequestSenderInterface $apiRequestSender, JsonToArrayTransformerInterface $responseTransformer, ArrayToJsonTransformerInterface $requestTransformer)
    {
        $this->apiRequestSender = $apiRequestSender;
        $this->responseTransformer = $responseTransformer;
        $this->requestTransformer = $requestTransformer;
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     *
     * @throws ConnectExceptionInterface
     * @throws ParseJsonExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     *
     * @return array<array-key, mixed>
     */
    public function get(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): array
    {
        $contents = $this->apiRequestSender->get($requestUrl, $requestQueryStrings, $requestHeaders);
        $doc = $this->responseTransformer->transform($contents, ApiRequestSenderInterface::METHOD_GET, $requestUrl, $requestQueryStrings);

        return $doc;
    }

    /**
     * @param string                       $requestUrl          The request URL
     * @param array<string, string>        $requestQueryStrings
     * @param array<string, string>        $requestHeaders
     * @param null|array<array-key, mixed> $requestBodyArray
     *
     * @throws ConnectExceptionInterface
     * @throws ParseJsonExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     *
     * @return array<array-key, mixed>
     */
    public function post(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?array $requestBodyArray = null): array
    {
        $requestBodyString = null;
        if (null !== $requestBodyArray) {
            $requestBodyString = $this->requestTransformer->transform($requestBodyArray, ApiRequestSenderInterface::METHOD_POST, $requestUrl, $requestQueryStrings);
        }
        $contents = $this->apiRequestSender->post($requestUrl, $requestQueryStrings, $requestHeaders, $requestBodyString);
        $doc = $this->responseTransformer->transform($contents, ApiRequestSenderInterface::METHOD_POST, $requestUrl, $requestQueryStrings);

        return $doc;
    }

    /**
     * @param string                $requestUrl          The request URL
     * @param array<string, string> $requestQueryStrings
     * @param array<string, string> $requestHeaders
     * @param array<string, string> $requestBodyFormData
     *
     * @throws ConnectExceptionInterface
     * @throws ParseJsonExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     *
     * @return array<array-key, mixed>
     */
    public function postForm(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyFormData = []): array
    {
        $contents = $this->apiRequestSender->postForm($requestUrl, $requestQueryStrings, $requestHeaders, $requestBodyFormData);
        $doc = $this->responseTransformer->transform($contents, ApiRequestSenderInterface::METHOD_POST, $requestUrl, $requestQueryStrings);

        return $doc;
    }
}
