<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use JsonException;
use Throwable;

use function http_build_query;
use function json_decode;
use function sprintf;

use const JSON_THROW_ON_ERROR;

final class JsonApiRequestSender implements JsonApiRequestSenderInterface
{
    private ClientInterface $guzzle;

    public function __construct(ClientInterface $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    public function get(string $url, array $queryStrings = [], array $headers = []): array
    {
        return $this->sendRequest(self::METHOD_GET, $url, $queryStrings, $headers);
    }

    public function post(string $url, array $queryStrings = [], array $headers = [], ?string $body = null): array
    {
        return $this->sendRequest(self::METHOD_POST, $url, $queryStrings, $headers, $body);
    }

    public function postData(string $url, array $queryStrings = [], array $headers = [], array $bodyData = []): array
    {
        $body = http_build_query($bodyData, '', '&');
        $data = $this->post($url, $queryStrings, $headers, $body);

        return $data;
    }

    private function sendRequest(string $method, string $url, array $queryStrings = [], array $headers = [], ?string $body = null): array
    {
        $finalUrl = $url;
        if (!empty($queryStrings)) {
            $queryStringsFlat = http_build_query($queryStrings, '', '&');
            $finalUrl = sprintf('%s?%s', $url, $queryStringsFlat);
        }
        $request = new Request($method, $finalUrl, $headers, $body);

        try {
            $response = $this->guzzle->send($request);
        } catch (ConnectException $exception) {
            throw new JsonApiRequestException($request, null, self::ERROR_CONNECTION, $exception);
        } catch (BadResponseException $exception) {
            $errorResponse = $exception->getResponse();

            throw new JsonApiRequestException($request, $errorResponse, self::ERROR_BAD_RESPONSE, $exception, $errorResponse->getStatusCode());
        } catch (Throwable $exception) {
            throw new JsonApiRequestException($request, null, self::ERROR_UNHANDLED, $exception);
        }

        $body = $response->getBody();
        $contents = $body->getContents();

        try {
            $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            throw new JsonApiRequestException($request, $response, self::ERROR_JSON_DECODE, $exception);
        }

        return $data;
    }
}
