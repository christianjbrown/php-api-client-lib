<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests;

use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Transformer\StringToXmlDocTransformerInterface;
use ChristianBrown\ApiClient\Transformer\XmlDocToStringTransformerInterface;
use ChristianBrown\ApiClient\XmlApiRequestSender;
use DOMDocument;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

#[CoversClass(XmlApiRequestSender::class)]
final class XmlApiRequestSenderTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testGet(): void
    {
        $responseDoc = new DOMDocument();

        $apiRequestSender = $this->createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->method('get')
            ->with(
                'test-url',
                ['test-query-string' => 'test-value'],
                ['test-header' => 'test-value']
            )
            ->willReturn('test-response');

        $responseTransformer = $this->createMock(StringToXmlDocTransformerInterface::class);
        $responseTransformer->method('transform')
            ->with(
                'test-response',
                ApiRequestSenderInterface::METHOD_GET,
                'test-url',
                ['test-query-string' => 'test-value'],
            )
            ->willReturn($responseDoc);

        $requestTransformer = $this->createMock(XmlDocToStringTransformerInterface::class);
        $xmlApiRequestSender = new XmlApiRequestSender($apiRequestSender, $responseTransformer, $requestTransformer);
        $actual = $xmlApiRequestSender->get('test-url', ['test-query-string' => 'test-value'], ['test-header' => 'test-value']);

        self::assertSame($responseDoc, $actual);
    }

    /**
     * @throws Exception
     */
    public function testPost(): void
    {
        $requestDoc = new DOMDocument();
        $responseDoc = new DOMDocument();

        $apiRequestSender = $this->createMock(ApiRequestSenderInterface::class);
        $apiRequestSender->method('post')
            ->with(
                'test-url',
                ['test-query-string' => 'test-value'],
                ['test-header' => 'test-value'],
                'test-request-body'
            )
            ->willReturn('test-response');

        $responseTransformer = $this->createMock(StringToXmlDocTransformerInterface::class);
        $responseTransformer->method('transform')
            ->with(
                'test-response',
                ApiRequestSenderInterface::METHOD_POST,
                'test-url',
                ['test-query-string' => 'test-value'],
            )
            ->willReturn($responseDoc);

        $requestTransformer = $this->createMock(XmlDocToStringTransformerInterface::class);
        $requestTransformer->method('transform')
            ->with(
                $requestDoc,
                ApiRequestSenderInterface::METHOD_POST,
                'test-url',
                ['test-query-string' => 'test-value'],
            )
            ->willReturn('test-request-body');

        $xmlApiRequestSender = new XmlApiRequestSender($apiRequestSender, $responseTransformer, $requestTransformer);
        $actual = $xmlApiRequestSender->post('test-url', ['test-query-string' => 'test-value'], ['test-header' => 'test-value'], $requestDoc);

        self::assertSame($responseDoc, $actual);
    }
}
