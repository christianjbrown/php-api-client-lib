<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

use GuzzleHttp\Exception\TooManyRedirectsException as GuzzleTooManyRedirectsException;
use Psr\Http\Message\RequestInterface;

use function sprintf;

final class TooManyRedirectsException extends AbstractResponseException implements TooManyRedirectsExceptionInterface
{
    public function __construct(RequestInterface $request, GuzzleTooManyRedirectsException $exception)
    {
        $response = $exception->getResponse();
        $message = sprintf(self::MESSAGE, $request->getUri()->__toString());
        parent::__construct($request, $response, $message, $exception);
    }
}
