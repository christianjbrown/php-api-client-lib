<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests;

use ChristianBrown\ApiClient\ApiRequestSender;
use ChristianBrown\ApiClient\ApiRequestSenderInterface;
use ChristianBrown\ApiClient\Exception\Parse\ParseJsonException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use ChristianBrown\ApiClient\Exception\Request\ConnectException;
use ChristianBrown\ApiClient\Exception\Request\ConnectExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\ResponseException;
use ChristianBrown\ApiClient\Exception\Response\ResponseExceptionInterface;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsException;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsExceptionInterface;
use ChristianBrown\ApiClient\Model\ApiFormat;
use ChristianBrown\ApiClient\Transformer\DataToArrayTransformerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\GuzzleException;
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
#[CoversClass(ResponseException::class)]
#[CoversClass(TooManyRedirectsException::class)]
final class ApiRequestSenderTest extends TestCase
{
    /**
     * @throws ConnectException
     * @throws Exception
     * @throws GuzzleException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], ApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['postData', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key-1' => 'test-body-value-1']], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key-1=test-body-value-1', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    public function testBadResponseException(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $guzzleBadResponseException = $this->createMock(GuzzleBadResponseException::class);

        $requestSender = $this->getRequestSenderForException($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $guzzleBadResponseException);
        $responseExceptionThrown = false;

        try {
            $requestSender->{$function}(...$functionArgs);
        } catch (ResponseExceptionInterface $e) {
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
     * @throws GuzzleException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], ApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['postData', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key-1' => 'test-body-value-1']], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key-1=test-body-value-1', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
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
     * @throws GuzzleException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], ApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['postData', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key-1' => 'test-body-value-1']], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key-1=test-body-value-1', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    public function testSuccess(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $thisRequest = null;
        $thisResponse = null;

        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->method('send')
            ->willReturnCallback(
                function (RequestInterface $request) use ($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $throwException, &$thisRequest, &$thisResponse) {
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

                    if ($throwException) {
                        throw $throwException;
                    }

                    $thisRequest = $request;
                    $thisResponse = $response;

                    return $response;
                }
            );

        $responseTransformer = $this->createMock(DataToArrayTransformerInterface::class);
        $responseTransformer->method('transform')
            ->willReturnCallback(
                static function (string $rawData, RequestInterface $request, ResponseInterface $response) use (&$thisRequest, &$thisResponse) {
                    self::assertSame($thisRequest, $request);
                    self::assertSame($thisResponse, $response);

                    return ['test-data'];
                }
            );

        $requestSender = new ApiRequestSender($guzzle, $responseTransformer);
        $actual = $requestSender->{$function}(...$functionArgs);
        self::assertSame(['test-data'], $actual);
    }

    /**
     * @throws ConnectException
     * @throws Exception
     * @throws GuzzleException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], ApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
    #[TestWith(['postData', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key-1' => 'test-body-value-1']], ApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key-1=test-body-value-1', 'test-url?test-query-string-key-1=test-query-string-value-1', '{"test-response-key-1": "test-response-value-1"}', ['test-response-key-1' => 'test-response-value-1']])]
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

        $responseTransformer = $this->createMock(DataToArrayTransformerInterface::class);
        $responseTransformer->expects(self::never())
            ->method('transform');

        $requestSender = new ApiRequestSender($guzzle, $responseTransformer);

        return $requestSender;
    }
}
