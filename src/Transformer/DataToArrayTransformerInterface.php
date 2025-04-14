<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface DataToArrayTransformerInterface
{
    public function transform(string $rawData, RequestInterface $request, ResponseInterface $response): array;
}
