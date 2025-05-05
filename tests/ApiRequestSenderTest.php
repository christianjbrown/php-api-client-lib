<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests;

use ChristianBrown\ApiClient\ApiRequestSender;
use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Parse\ParseJsonException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use ChristianBrown\ApiClient\Exception\Request\ConnectException;
use ChristianBrown\ApiClient\Exception\Request\ConnectExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\BadResponseException;
use ChristianBrown\ApiClient\Exception\Response\BadResponseExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsException;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsExceptionInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\TooManyRedirectsException as GuzzleTooManyRedirectsException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Throwable;

#[CoversClass(ApiRequestSender::class)]
#[CoversClass(ParseJsonException::class)]
#[CoversClass(ParseXmlException::class)]
#[CoversClass(ConnectException::class)]
#[CoversClass(BadResponseException::class)]
#[CoversClass(TooManyRedirectsException::class)]
final class ApiRequestSenderTest extends TestCase
{
    /**
     * @throws ConnectException
     * @throws Exception
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], ApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['postData', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key-1' => 'test-body-value-1']], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key-1=test-body-value-1', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    public function testBadResponseException(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $guzzleBadResponseException = $this->createMock(GuzzleBadResponseException::class);

        $requestSender = $this->getRequestSenderForException($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $guzzleBadResponseException);
        $responseExceptionThrown = false;

        try {
            $requestSender->{$function}(...$functionArgs);
        } catch (BadResponseExceptionInterface $e) {
            $responseExceptionThrown = true;

            $request = $e->getRequest();
            self::assertSame($expectedRequestMethod, $request->getMethod());
            self::assertSame($expectedHeaders, $request->getHeaders());
            $requestBody = $request->getBody();
            $requestBody->rewind();
            self::assertSame($expectedRequestBody, $requestBody->getContents());
            self::assertSame($expectedRequestUrl, $request->getUri()->__toString());

            self::assertSame($guzzleBadResponseException, $e->getPrevious());
        }

        self::assertTrue($responseExceptionThrown);
    }

    /**
     * @throws ConnectException
     * @throws Exception
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], ApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['postData', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key-1' => 'test-body-value-1']], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key-1=test-body-value-1', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    public function testConnectException(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $guzzleConnectException = $this->createMock(GuzzleConnectException::class);

        $requestSender = $this->getRequestSenderForException($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $guzzleConnectException);
        $connectExceptionThrown = false;

        try {
            $requestSender->{$function}(...$functionArgs);
        } catch (ConnectExceptionInterface $e) {
            $connectExceptionThrown = true;

            $request = $e->getRequest();
            self::assertSame($expectedRequestMethod, $request->getMethod());
            self::assertSame($expectedHeaders, $request->getHeaders());
            $body = $request->getBody();
            $body->rewind();
            self::assertSame($expectedRequestBody, $body->getContents());
            self::assertSame($expectedRequestUrl, $request->getUri()->__toString());

            self::assertSame($guzzleConnectException, $e->getPrevious());
        }

        self::assertTrue($connectExceptionThrown);
    }

    /**
     * @throws ConnectException
     * @throws Exception
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], ApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['postData', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key-1' => 'test-body-value-1']], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key-1=test-body-value-1', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    public function testSuccess(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->method('send')
            ->willReturnCallback(
                function (RequestInterface $request) use ($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent) {
                    self::assertSame($expectedRequestMethod, $request->getMethod());
                    self::assertSame($expectedHeaders, $request->getHeaders());
                    self::assertSame($expectedRequestBody, $request->getBody()->getContents());
                    self::assertSame($expectedRequestUrl, $request->getUri()->__toString());

                    $responseSteam = $this->createMock(StreamInterface::class);
                    $responseSteam->method('getContents')
                        ->willReturn($responseBodyContent);
                    $response = $this->createMock(ResponseInterface::class);
                    $response->method('getStatusCode')
                        ->willReturn(42);
                    $response->method('getBody')
                        ->willReturn($responseSteam);

                    return $response;
                }
            );

        $requestSender = new ApiRequestSender($guzzle);
        $actual = $requestSender->{$function}(...$functionArgs);
        self::assertSame('test-response', $actual);
    }

    /**
     * @throws ConnectException
     * @throws Exception
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], ApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['postData', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key-1' => 'test-body-value-1']], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key-1=test-body-value-1', 'test-url?test-query-string-key-1=test-query-string-value-1', 'test-response', ['test-response-key-1' => 'test-response-value-1']])]
    public function testTooManyRedirectsException(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $guzzleTooManyRedirectsException = $this->createMock(GuzzleTooManyRedirectsException::class);
        $guzzleTooManyRedirectsException->method('getResponse')
            ->willReturn($response);

        $requestSender = $this->getRequestSenderForException($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $guzzleTooManyRedirectsException);
        $tooManyRedirectsExceptionThrown = false;

        try {
            $requestSender->{$function}(...$functionArgs);
        } catch (TooManyRedirectsExceptionInterface $e) {
            $tooManyRedirectsExceptionThrown = true;

            $request = $e->getRequest();
            self::assertSame($expectedRequestMethod, $request->getMethod());
            self::assertSame($expectedHeaders, $request->getHeaders());
            $requestBody = $request->getBody();
            $requestBody->rewind();
            self::assertSame($expectedRequestBody, $requestBody->getContents());
            self::assertSame($expectedRequestUrl, $request->getUri()->__toString());

            self::assertSame($guzzleTooManyRedirectsException, $e->getPrevious());
        }

        self::assertTrue($tooManyRedirectsExceptionThrown);
    }

    /**
     * @throws Exception
     */
    private function getRequestSenderForException(string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent, ?Throwable $throwException = null): ApiRequestSenderInterface
    {
        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->method('send')
            ->willReturnCallback(
                function (RequestInterface $request) use ($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $throwException): void {
                    self::assertSame($expectedRequestMethod, $request->getMethod());
                    self::assertSame($expectedHeaders, $request->getHeaders());
                    self::assertSame($expectedRequestBody, $request->getBody()->getContents());
                    self::assertSame($expectedRequestUrl, $request->getUri()->__toString());

                    $responseSteam = $this->createMock(StreamInterface::class);
                    $responseSteam->method('getContents')
                        ->willReturn($responseBodyContent);
                    $response = $this->createMock(ResponseInterface::class);
                    $response->method('getStatusCode')
                        ->willReturn(42);
                    $response->method('getBody')
                        ->willReturn($responseSteam);

                    throw $throwException;
                }
            );

        $requestSender = new ApiRequestSender($guzzle);

        return $requestSender;
    }
}
