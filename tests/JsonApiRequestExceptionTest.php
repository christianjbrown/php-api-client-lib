<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient\Tests;

use ChristianBrown\JsonApiClient\JsonApiRequestException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

#[CoversClass(JsonApiRequestException::class)]
final class JsonApiRequestExceptionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $previousException = new RuntimeException('test-previous-exception');
        $exception = new JsonApiRequestException($request, $response, 'test-message', $previousException, 42);
        self::assertSame($request, $exception->getRequest());
        self::assertSame($response, $exception->getResponse());
        self::assertSame('test-message', $exception->getMessage());
        self::assertSame($previousException, $exception->getPrevious());
        self::assertSame(42, $exception->getCode());
    }
}
