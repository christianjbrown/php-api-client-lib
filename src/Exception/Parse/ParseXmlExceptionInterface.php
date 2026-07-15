<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Parse;

use LibXMLError;

interface ParseXmlExceptionInterface extends ParseExceptionInterface
{
    public const string MESSAGE_ERROR_SPRINTF = ' Line %d: "%s"';
    public const string MESSAGE_ERRORS_SEP = "\n";
    public const string MESSAGE_SPRINTF = "XML parsing error(s) whilst %sing to %s:\n%s";

    /**
     * @return array<int, LibXMLError>
     */
    public function getErrors(): array;
}
