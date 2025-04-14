<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests\Exception\Parse;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonException;
use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;
use JsonException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

#[CoversClass(ParseJsonException::class)]
final class ParseJsonExceptionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $jsonException = new JsonException('test-error-message');

        $exception = new ParseJsonException($request, $response, $jsonException);
        self::assertSame($request, $exception->getRequest());
        self::assertSame($response, $exception->getResponse());
        self::assertSame($jsonException, $exception->getJsonException());
        self::assertSame($jsonException, $exception->getPrevious());
        self::assertSame(sprintf(ParseJsonExceptionInterface::MESSAGE, 'test-error-message'), $exception->getMessage());
    }
}
