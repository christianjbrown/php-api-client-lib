<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformer;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(JsonToArrayTransformer::class)]
final class JsonToArrayTransformerTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $transformer = new JsonToArrayTransformer();
        $actual = $transformer->transform('{"test-key-1": "test-value-1"}', $request, $response);
        self::assertSame(['test-key-1' => 'test-value-1'], $actual);
    }

    /**
     * @throws Exception
     */
    public function testException(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $transformer = new JsonToArrayTransformer();
        $parseJsonExceptionThrown = false;

        try {
            $transformer->transform('{"test-invalid-json', $request, $response);
        } catch (ParseJsonExceptionInterface $e) {
            $parseJsonExceptionThrown = true;
            self::assertSame($request, $e->getRequest());
            self::assertSame($response, $e->getResponse());
        }
        self::assertTrue($parseJsonExceptionThrown);
    }
}
