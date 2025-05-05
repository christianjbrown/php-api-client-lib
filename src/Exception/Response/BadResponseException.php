<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Exception\Response;

use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use Psr\Http\Message\RequestInterface;

use function sprintf;

final class BadResponseException extends AbstractResponseException implements BadResponseExceptionInterface
{
    public function __construct(RequestInterface $request, GuzzleBadResponseException $exception)
    {
        $response = $exception->getResponse();
        $message = sprintf(self::MESSAGE, $request->getUri()->__toString(), $response->getStatusCode());
        parent::__construct($request, $response, $message, $exception);
    }
}
