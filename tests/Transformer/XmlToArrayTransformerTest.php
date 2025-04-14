<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests\Transformer;

use ChristianBrown\ApiClient\Exception\AbstractException;
use ChristianBrown\ApiClient\Exception\Parse\AbstractParseException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlExceptionInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformerInterface;
use ChristianBrown\ApiClient\Transformer\XmlToArrayTransformer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(AbstractException::class)]
#[CoversClass(AbstractParseException::class)]
#[CoversClass(ParseXmlException::class)]
#[CoversClass(XmlToArrayTransformer::class)]
final class XmlToArrayTransformerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $jsonToArrayTransformer = $this->createMock(JsonToArrayTransformerInterface::class);
        $jsonToArrayTransformer->method('transform')
            ->with('{"test-key-1":"test-value-1"}', $request, $response)
            ->willReturn(['test-data']);

        $transformer = new XmlToArrayTransformer($jsonToArrayTransformer);
        $actual = $transformer->transform('<xml><test-key-1>test-value-1</test-key-1></xml>', $request, $response);

        self::assertSame(['test-data'], $actual);
    }

    /**
     * @throws Exception
     */
    public function testException(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $jsonToArrayTransformer = $this->createMock(JsonToArrayTransformerInterface::class);
        $jsonToArrayTransformer->expects(self::never())
            ->method('transform');

        $transformer = new XmlToArrayTransformer($jsonToArrayTransformer);

        $parseXmlExceptionThrown = false;

        try {
            $transformer->transform('<xml><test-invalid-xml', $request, $response);
        } catch (ParseXmlExceptionInterface $e) {
            $parseXmlExceptionThrown = true;
            self::assertSame($request, $e->getRequest());
            self::assertSame($response, $e->getResponse());
        }
        self::assertTrue($parseXmlExceptionThrown);
    }
}
