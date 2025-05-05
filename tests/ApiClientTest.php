<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests;

use ChristianBrown\ApiClient\ApiClient;
use ChristianBrown\ApiClient\ApiRequestSender;
use ChristianBrown\ApiClient\JsonApiRequestSender;
use ChristianBrown\ApiClient\Transformer\ArrayToJsonTransformer;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformer;
use ChristianBrown\ApiClient\Transformer\StringToXmlDocTransformer;
use ChristianBrown\ApiClient\Transformer\XmlDocToStringTransformer;
use ChristianBrown\ApiClient\XmlApiRequestSender;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[CoversClass(ApiClient::class)]
#[CoversClass(ApiRequestSender::class)]
#[CoversClass(ArrayToJsonTransformer::class)]
#[CoversClass(JsonApiRequestSender::class)]
#[CoversClass(JsonToArrayTransformer::class)]
#[CoversClass(StringToXmlDocTransformer::class)]
#[CoversClass(XmlApiRequestSender::class)]
#[CoversClass(XmlDocToStringTransformer::class)]
final class ApiClientTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function test(): void
    {
        $apiClient = new ApiClient();

        $apiRequestSender = $apiClient->getApiRequestSender();
        self::assertInstanceOf(ApiRequestSender::class, $apiRequestSender);

        $jsonApiRequestSender = $apiClient->getJsonApiRequestSender();
        self::assertInstanceOf(JsonApiRequestSender::class, $jsonApiRequestSender);

        $xmlApiRequestSender = $apiClient->getXmlApiRequestSender();
        self::assertInstanceOf(XmlApiRequestSender::class, $xmlApiRequestSender);
    }
}
