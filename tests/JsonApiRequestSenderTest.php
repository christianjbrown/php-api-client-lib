<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient\Tests;

use ChristianBrown\JsonApiClient\JsonApiRequestException;
use ChristianBrown\JsonApiClient\JsonApiRequestExceptionInterface;
use ChristianBrown\JsonApiClient\JsonApiRequestSender;
use ChristianBrown\JsonApiClient\JsonApiRequestSenderInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use JsonException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use RuntimeException;
use Throwable;

#[CoversClass(JsonApiRequestException::class)]
#[CoversClass(JsonApiRequestSender::class)]
final class JsonApiRequestSenderTest extends TestCase
{
    /**
     * @throws Exception
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], JsonApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], '{"test-body-key-1": "test-body-value-1"}'], JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], '{"test-body-key-1": "test-body-value-1"}', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    public function testBadResponseException(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $throwException = $this->createMock(BadResponseException::class);
        $throwException->method('getResponse')
            ->willReturn($response);

        $requestSender = $this->getRequestSender($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $throwException);

        $exceptionThrown = false;

        try {
            $requestSender->{$function}(...$functionArgs);
        } catch (JsonApiRequestExceptionInterface $e) {
            $exceptionThrown = true;
            self::assertSame(JsonApiRequestSenderInterface::ERROR_BAD_RESPONSE, $e->getMessage());
            self::assertSame($response, $e->getResponse());
            self::assertSame(0, $e->getCode());
            self::assertSame($throwException, $e->getPrevious());
        }

        self::assertTrue($exceptionThrown);
    }

    /**
     * @throws Exception
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], JsonApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], '{"test-body-key-1": "test-body-value-1"}'], JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], '{"test-body-key-1": "test-body-value-1"}', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    public function testConnectException(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $throwException = $this->createMock(ConnectException::class);
        $requestSender = $this->getRequestSender($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $throwException);

        $exceptionThrown = false;

        try {
            $requestSender->{$function}(...$functionArgs);
        } catch (JsonApiRequestExceptionInterface $e) {
            $exceptionThrown = true;
            self::assertSame(JsonApiRequestSenderInterface::ERROR_CONNECTION, $e->getMessage());
            self::assertSame(0, $e->getCode());
            self::assertSame($throwException, $e->getPrevious());
        }

        self::assertTrue($exceptionThrown);
    }

    /**
     * @throws Exception
     */
    public function testGet(): void
    {
        $requestSender = $this->getRequestSender(JsonApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]');
        $actual = $requestSender->get('test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']);

        self::assertSame(['test-response'], $actual);
    }

    /**
     * @throws Exception
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], JsonApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1'])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1'])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], '{"test-body-key-1": "test-body-value-1"}'], JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], '{"test-body-key-1": "test-body-value-1"}', 'test-url?test-query-string-key-1=test-query-string-value-1'])]
    public function testJsonException(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl): void
    {
        $requestSender = $this->getRequestSender($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, '["');

        $exceptionThrown = false;

        try {
            $requestSender->{$function}(...$functionArgs);
        } catch (JsonApiRequestExceptionInterface $e) {
            $exceptionThrown = true;
            self::assertSame(JsonApiRequestSenderInterface::ERROR_JSON_DECODE, $e->getMessage());
            self::assertSame(0, $e->getCode());
            self::assertInstanceOf(JsonException::class, $e->getPrevious());
        }

        self::assertTrue($exceptionThrown);
    }

    /**
     * @throws Exception
     */
    public function testPost(): void
    {
        $requestSender = $this->getRequestSender(JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]');
        $actual = $requestSender->post('test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body');

        self::assertSame(['test-response'], $actual);
    }

    /**
     * @throws Exception
     */
    public function testPostData(): void
    {
        $requestSender = $this->getRequestSender(JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body-key=test-body-value', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]');
        $actual = $requestSender->postData('test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], ['test-body-key' => 'test-body-value']);

        self::assertSame(['test-response'], $actual);
    }

    /**
     * @throws Exception
     */
    #[TestWith(['get', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1']], JsonApiRequestSenderInterface::METHOD_GET, [['test-header-1']], '', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], 'test-body'], JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], 'test-body', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    #[TestWith(['post', ['test-url', ['test-query-string-key-1' => 'test-query-string-value-1'], ['test-header-1'], '{"test-body-key-1": "test-body-value-1"}'], JsonApiRequestSenderInterface::METHOD_POST, [['test-header-1']], '{"test-body-key-1": "test-body-value-1"}', 'test-url?test-query-string-key-1=test-query-string-value-1', '["test-response"]'])]
    public function testUnhandledException(string $function, array $functionArgs, string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent): void
    {
        $throwException = $this->createMock(RuntimeException::class);
        $requestSender = $this->getRequestSender($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $throwException);

        $exceptionThrown = false;

        try {
            $requestSender->{$function}(...$functionArgs);
        } catch (JsonApiRequestExceptionInterface $e) {
            $exceptionThrown = true;
            self::assertSame(JsonApiRequestSenderInterface::ERROR_UNHANDLED, $e->getMessage());
            self::assertSame(0, $e->getCode());
            self::assertSame($throwException, $e->getPrevious());
        }

        self::assertTrue($exceptionThrown);
    }

    /**
     * @throws Exception
     */
    private function getRequestSender(string $expectedRequestMethod, array $expectedHeaders, string $expectedRequestBody, string $expectedRequestUrl, string $responseBodyContent, ?Throwable $throwException = null): JsonApiRequestSenderInterface
    {
        $guzzle = $this->createMock(ClientInterface::class);
        $guzzle->method('send')
            ->willReturnCallback(
                function (RequestInterface $request) use ($expectedRequestMethod, $expectedHeaders, $expectedRequestBody, $expectedRequestUrl, $responseBodyContent, $throwException) {
                    self::assertSame($expectedRequestMethod, $request->getMethod());
                    self::assertSame($expectedHeaders, $request->getHeaders());
                    self::assertSame($expectedRequestBody, $request->getBody()->getContents());
                    self::assertSame($expectedRequestUrl, $request->getUri()->__toString());

                    $responseSteam = $this->createMock(StreamInterface::class);
                    $responseSteam->method('getContents')
                        ->willReturn($responseBodyContent);
                    $response = $this->createMock(ResponseInterface::class);
                    $response->method('getBody')
                        ->willReturn($responseSteam);

                    if ($throwException) {
                        throw $throwException;
                    }

                    return $response;
                }
            );

        $requestSender = new JsonApiRequestSender($guzzle);

        return $requestSender;
    }
}
