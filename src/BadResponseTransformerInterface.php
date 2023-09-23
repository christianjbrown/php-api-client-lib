<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient;

use Psr\Http\Message\ResponseInterface;

interface BadResponseTransformerInterface
{
    public function getFriendlyErrorFromBadResponse(ResponseInterface $response): string;

    public function getFriendlyErrorFromBadResponseJsonData(ResponseInterface $response, array $responseData): string;
}
