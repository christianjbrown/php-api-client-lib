<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests;

use ChristianBrown\ApiClient\ApiClient;
use ChristianBrown\ApiClient\ApiRequestSender;
use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformer;
use ChristianBrown\ApiClient\Transformer\XmlToArrayTransformer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

#[CoversClass(ApiClient::class)]
#[CoversClass(ApiRequestSender::class)]
#[CoversClass(JsonToArrayTransformer::class)]
#[CoversClass(XmlToArrayTransformer::class)]
final class ApiClientTest extends TestCase
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function test(): void
    {
        $apiClient = new ApiClient();

        $apiSenderJson = $apiClient->getApiRequestSenderForJson();
        self::assertInstanceOf(ApiRequestSenderInterface::class, $apiSenderJson);

        $apiSenderXml = $apiClient->getApiRequestSenderForXml();
        self::assertInstanceOf(ApiRequestSenderInterface::class, $apiSenderXml);
    }
}
