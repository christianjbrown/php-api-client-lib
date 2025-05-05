<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Exception\Parse\ParseXmlExceptionInterface;
use ChristianBrown\ApiClient\Exception\Request\ConnectExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\BadResponseExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsExceptionInterface;
use DOMDocument;

interface XmlApiRequestSenderInterface
{
    /**
     * @throws ConnectExceptionInterface
     * @throws ParseXmlExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     */
    public function get(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): DOMDocument;

    /**
     * @throws ConnectExceptionInterface
     * @throws ParseXmlExceptionInterface
     * @throws BadResponseExceptionInterface
     * @throws TooManyRedirectsExceptionInterface
     */
    public function post(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?DOMDocument $requestDomDocument = null): DOMDocument;
}
