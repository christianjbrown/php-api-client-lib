<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\JsonApiRequestSender;
use ChristianBrown\ApiClient\Transformer\ArrayToJsonTransformerInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonApiRequestSender::class)]
final class JsonApiRequestSenderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGet(): void
    {
        $apiRequestSender = $this->createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->method('get')
            ->with(
                'test-url',
                ['test-query-string' => 'test-value'],
                ['test-header' => 'test-value']
            )
            ->willReturn('test-response');

        $responseTransformer = $this->createMock(JsonToArrayTransformerInterface::class);
        $responseTransformer->method('transform')
            ->with(
                'test-response',
                ApiRequestSenderInterface::METHOD_GET,
                'test-url',
                ['test-query-string' => 'test-value'],
            )
            ->willReturn(['test-response-array']);

        $requestTransformer = $this->createMock(ArrayToJsonTransformerInterface::class);
        $jsonApiRequestSender = new JsonApiRequestSender($apiRequestSender, $responseTransformer, $requestTransformer);
        $actual = $jsonApiRequestSender->get('test-url', ['test-query-string' => 'test-value'], ['test-header' => 'test-value']);

        self::assertSame(['test-response-array'], $actual);
    }

    /**
     * @throws Exception
     */
    public function testPost(): void
    {
        $apiRequestSender = $this->createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->method('post')
            ->with(
                'test-url',
                ['test-query-string' => 'test-value'],
                ['test-header' => 'test-value'],
                'test-request-body'
            )
            ->willReturn('test-response');

        $responseTransformer = $this->createMock(JsonToArrayTransformerInterface::class);
        $responseTransformer->method('transform')
            ->with(
                'test-response',
                ApiRequestSenderInterface::METHOD_POST,
                'test-url',
                ['test-query-string' => 'test-value'],
            )
            ->willReturn(['test-response-array']);

        $requestTransformer = $this->createMock(ArrayToJsonTransformerInterface::class);
        $requestTransformer->method('transform')
            ->with(
                ['test-request-array'],
                ApiRequestSenderInterface::METHOD_POST,
                'test-url',
                ['test-query-string' => 'test-value'],
            )
            ->willReturn('test-request-body');

        $jsonApiRequestSender = new JsonApiRequestSender($apiRequestSender, $responseTransformer, $requestTransformer);
        $actual = $jsonApiRequestSender->post('test-url', ['test-query-string' => 'test-value'], ['test-header' => 'test-value'], ['test-request-array']);

        self::assertSame(['test-response-array'], $actual);
    }
}
