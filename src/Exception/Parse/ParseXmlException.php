<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use LibXMLError;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

use function implode;
use function sprintf;

final class ParseXmlException extends AbstractParseException implements ParseXmlExceptionInterface
{
    /**
     * @var LibXMLError[]
     */
    private array $errors = [];

    public function __construct(RequestInterface $request, ResponseInterface $response, array $errors)
    {
        foreach ($errors as $error) {
            if ($error instanceof LibXMLError) {
                $this->errors[] = $error;
            }
        }
        $message = self::generateMessage($this->errors);
        parent::__construct($request, $response, $message);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private static function generateMessage(array $errors): string
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
            implode(self::MESSAGE_ERRORS_SEP, $errorMessages)
        );

        return $message;
    }
}
