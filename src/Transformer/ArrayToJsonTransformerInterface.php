<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient\Transformer;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonExceptionInterface;

interface ArrayToJsonTransformerInterface
{
    /**
     * @throws ParseJsonExceptionInterface
     */
    public function transform(array $data, string $method, string $requestUrl, array $requestQueryStrings = []): string;
}
