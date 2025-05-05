<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests\Exception\Response;

use ChristianBrown\ApiClient\Exception\Response\BadResponseException;
use ChristianBrown\ApiClient\Exception\Response\BadResponseExceptionInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

#[CoversClass(BadResponseException::class)]
final class BadResponseExceptionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $requestUri = $this->createMock(UriInterface::class);
        $requestUri->method('__toString')
            ->willReturn('https://test.com/');
        $request = $this->createMock(RequestInterface::class);
        $request->method('getUri')
            ->willReturn($requestUri);

        $response = $this->createMock(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn(42);
        $guzzleBadResponseException = $this->createMock(GuzzleBadResponseException::class);
        $guzzleBadResponseException->method('getResponse')
            ->willReturn($response);

        $exception = new BadResponseException($request, $guzzleBadResponseException);
        self::assertSame($request, $exception->getRequest());
        self::assertSame($response, $exception->getResponse());
        self::assertSame(sprintf(BadResponseExceptionInterface::MESSAGE, 'https://test.com/', 42), $exception->getMessage());
        self::assertSame($guzzleBadResponseException, $exception->getPrevious());
    }
}
