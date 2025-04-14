<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests\Exception\Parse;

use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlExceptionInterface;
use LibXMLError;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use function implode;
use function sprintf;

#[CoversClass(ParseXmlException::class)]
final class ParseXmlExceptionTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testEmptyErrors(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $errors = [];

        $exception = new ParseXmlException($request, $response, $errors);
        self::assertSame($request, $exception->getRequest());
        self::assertSame($response, $exception->getResponse());
        self::assertSame($errors, $exception->getErrors());
        $expectedMessage = sprintf(
            ParseXmlExceptionInterface::MESSAGE_SPRINTF,
            implode(ParseXmlExceptionInterface::MESSAGE_ERRORS_SEP, [])
        );
        self::assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testMultipleErrors(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $error1 = $this->createMock(LibXMLError::class);
        $error1->line = 42;
        $error1->message = 'test-error-1';
        $error2 = $this->createMock(LibXMLError::class);
        $error2->line = 43;
        $error2->message = 'test-error-2';
        $errors = [$error1, $error2];

        $exception = new ParseXmlException($request, $response, $errors);
        self::assertSame($request, $exception->getRequest());
        self::assertSame($response, $exception->getResponse());
        self::assertSame($errors, $exception->getErrors());
        $errorsMessages = [];
        foreach ($errors as $error) {
            $errorsMessages[] = sprintf(
                ParseXmlExceptionInterface::MESSAGE_ERROR_SPRINTF,
                $error->line,
                $error->message
            );
        }
        $expectedMessage = sprintf(
            ParseXmlExceptionInterface::MESSAGE_SPRINTF,
            implode(ParseXmlExceptionInterface::MESSAGE_ERRORS_SEP, $errorsMessages)
        );
        self::assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testNotErrors(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $errors = ['test-not-a-libxml-error'];

        $exception = new ParseXmlException($request, $response, $errors);
        self::assertSame($request, $exception->getRequest());
        self::assertSame($response, $exception->getResponse());
        self::assertSame([], $exception->getErrors());
        $expectedMessage = sprintf(
            ParseXmlExceptionInterface::MESSAGE_SPRINTF,
            implode(ParseXmlExceptionInterface::MESSAGE_ERRORS_SEP, [])
        );
        self::assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testSingleError(): void
    {
        $request = $this->createMock(RequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);
        $error1 = $this->createMock(LibXMLError::class);
        $error1->line = 42;
        $error1->message = 'test-error-1';
        $errors = [$error1];

        $exception = new ParseXmlException($request, $response, $errors);
        self::assertSame($request, $exception->getRequest());
        self::assertSame($response, $exception->getResponse());
        self::assertSame($errors, $exception->getErrors());
        $errorsMessages = [];
        foreach ($errors as $error) {
            $errorsMessages[] = sprintf(
                ParseXmlExceptionInterface::MESSAGE_ERROR_SPRINTF,
                $error->line,
                $error->message
            );
        }
        $expectedMessage = sprintf(
            ParseXmlExceptionInterface::MESSAGE_SPRINTF,
            implode(ParseXmlExceptionInterface::MESSAGE_ERRORS_SEP, $errorsMessages)
        );
        self::assertSame($expectedMessage, $exception->getMessage());
    }
}
