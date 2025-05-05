<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use LibXMLError;

use function implode;
use function sprintf;

final class ParseXmlException extends AbstractParseException implements ParseXmlExceptionInterface
{
    /**
     * @var LibXMLError[]
     */
    private array $errors = [];

    public function __construct(array $errors, string $method, string $requestUrl, ?array $requestQueryStrings = [])
    {
        foreach ($errors as $error) {
            if ($error instanceof LibXMLError) {
                $this->errors[] = $error;
            }
        }
        $message = self::generateMessage($this->errors, $method, $requestUrl);
        parent::__construct($message, $method, $requestUrl, $requestQueryStrings);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function generateMessage(array $errors, string $method, string $requestUrl): string
    {
        $errorMessages = [];
        foreach ($errors as $error) {
            $errorMessages[] = sprintf(
                self::MESSAGE_ERROR_SPRINTF,
                $error->line,
                $error->message
            );
        }

        $message = sprintf(
            self::MESSAGE_SPRINTF,
            $method,
            $requestUrl,
            implode(self::MESSAGE_ERRORS_SEP, $errorMessages)
        );

        return $message;
    }
}
