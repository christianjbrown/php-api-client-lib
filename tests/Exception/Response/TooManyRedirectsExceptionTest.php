<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests\Exception\Response;

use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsException;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsExceptionInterface;
use GuzzleHttp\Exception\TooManyRedirectsException as GuzzleTooManyRedirectsException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

#[CoversClass(TooManyRedirectsException::class)]
final class TooManyRedirectsExceptionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function test(): void
    {
        $requestUri = self::createStub(UriInterface::class);
        $requestUri->method('__toString')
            ->willReturn('https://test.com/');
        $request = self::createStub(RequestInterface::class);
        $request->method('getUri')
            ->willReturn($requestUri);

        $response = self::createStub(ResponseInterface::class);
        $response->method('getStatusCode')
            ->willReturn(42);
        $guzzleTooManyRedirectsException = self::createStub(GuzzleTooManyRedirectsException::class);
        $guzzleTooManyRedirectsException->method('getResponse')
            ->willReturn($response);

        $exception = new TooManyRedirectsException($request, $guzzleTooManyRedirectsException);
        self::assertSame($request, $exception->getRequest());
        self::assertSame($response, $exception->getResponse());
        self::assertSame(sprintf(TooManyRedirectsExceptionInterface::MESSAGE, 'https://test.com/'), $exception->getMessage());
        self::assertSame($guzzleTooManyRedirectsException, $exception->getPrevious());
    }

    /**
     * @throws Exception
     */
    public function testWithoutResponse(): void
    {
        $requestUri = self::createStub(UriInterface::class);
        $requestUri->method('__toString')
            ->willReturn('https://test.com/');
        $request = self::createStub(RequestInterface::class);
        $request->method('getUri')
            ->willReturn($requestUri);

        $guzzleTooManyRedirectsException = self::createStub(GuzzleTooManyRedirectsException::class);
        $guzzleTooManyRedirectsException->method('getResponse')
            ->willReturn(null);

        $exception = new TooManyRedirectsException($request, $guzzleTooManyRedirectsException);
        self::assertSame($request, $exception->getRequest());
        self::assertInstanceOf(ResponseInterface::class, $exception->getResponse());
        self::assertSame(TooManyRedirectsExceptionInterface::STATUS_CODE_TOO_MANY_REDIRECTS, $exception->getResponse()->getStatusCode());
        self::assertSame(TooManyRedirectsExceptionInterface::STATUS_CODE_TOO_MANY_REDIRECTS, $exception->getCode());
        self::assertSame(sprintf(TooManyRedirectsExceptionInterface::MESSAGE, 'https://test.com/'), $exception->getMessage());
        self::assertSame($guzzleTooManyRedirectsException, $exception->getPrevious());
    }
}
