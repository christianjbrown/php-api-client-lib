<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Tests\Exception\Parse;

use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlExceptionInterface;
use LibXMLError;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;

use function implode;
use function sprintf;

#[CoversClass(ParseXmlException::class)]
final class ParseXmlExceptionTest extends TestCase
{
    public function testEmptyErrors(): void
    {
        $errors = [];

        $exception = new ParseXmlException($errors, 'test-method', 'test-url', ['test-query-string' => 'test-value']);
        self::assertSame($errors, $exception->getErrors());
        self::assertSame('test-method', $exception->getMethod());
        self::assertSame('test-url', $exception->getUrl());
        self::assertSame(['test-query-string' => 'test-value'], $exception->getQueryStrings());
        $expectedMessage = sprintf(
            ParseXmlExceptionInterface::MESSAGE_SPRINTF,
            'test-method',
            'test-url',
            implode(ParseXmlExceptionInterface::MESSAGE_ERRORS_SEP, [])
        );
        self::assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testMultipleErrors(): void
    {
        $error1 = $this->createMock(LibXMLError::class);
        $error1->line = 42;
        $error1->message = 'test-error-1';
        $error2 = $this->createMock(LibXMLError::class);
        $error2->line = 43;
        $error2->message = 'test-error-2';
        $errors = [$error1, $error2];

        $exception = new ParseXmlException($errors, 'test-method', 'test-url', ['test-query-string' => 'test-value']);
        self::assertSame($errors, $exception->getErrors());
        self::assertSame('test-method', $exception->getMethod());
        self::assertSame('test-url', $exception->getUrl());
        self::assertSame(['test-query-string' => 'test-value'], $exception->getQueryStrings());

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
            'test-method',
            'test-url',
            implode(ParseXmlExceptionInterface::MESSAGE_ERRORS_SEP, $errorsMessages)
        );
        self::assertSame($expectedMessage, $exception->getMessage());
    }

    public function testNotErrors(): void
    {
        $errors = ['test-not-a-libxml-error'];

        $exception = new ParseXmlException($errors, 'test-method', 'test-url', ['test-query-string' => 'test-value']);
        self::assertSame([], $exception->getErrors());
        self::assertSame('test-method', $exception->getMethod());
        self::assertSame('test-url', $exception->getUrl());
        self::assertSame(['test-query-string' => 'test-value'], $exception->getQueryStrings());

        $expectedMessage = sprintf(
            ParseXmlExceptionInterface::MESSAGE_SPRINTF,
            'test-method',
            'test-url',
            implode(ParseXmlExceptionInterface::MESSAGE_ERRORS_SEP, [])
        );
        self::assertSame($expectedMessage, $exception->getMessage());
    }

    /**
     * @throws Exception
     */
    public function testSingleError(): void
    {
        $error1 = $this->createMock(LibXMLError::class);
        $error1->line = 42;
        $error1->message = 'test-error-1';
        $errors = [$error1];

        $exception = new ParseXmlException($errors, 'test-method', 'test-url', ['test-query-string' => 'test-value']);
        self::assertSame($errors, $exception->getErrors());
        self::assertSame('test-method', $exception->getMethod());
        self::assertSame('test-url', $exception->getUrl());
        self::assertSame(['test-query-string' => 'test-value'], $exception->getQueryStrings());

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
            'test-method',
            'test-url',
            implode(ParseXmlExceptionInterface::MESSAGE_ERRORS_SEP, $errorsMessages)
        );
        self::assertSame($expectedMessage, $exception->getMessage());
    }
}
