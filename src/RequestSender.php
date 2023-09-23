<?php

declare(strict_types=1);

namespace ChristianBrown\JsonApiClient;

use ChristianBrown\UserFriendlyException\UserFriendlyException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Request;
use JsonException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

use const JSON_THROW_ON_ERROR;

final class RequestSender implements RequestSenderInterface
{
    private Client $guzzle;
    private BadResponseTransformerInterface $jsonEndpointErrorResponseParser;

    public function __construct(BadResponseTransformerInterface $jsonEndpointErrorResponseParser)
    {
        $this->jsonEndpointErrorResponseParser = $jsonEndpointErrorResponseParser;
        $this->guzzle = new Client();
    }

    public function get(string $friendlyName, string $url, array $queryStrings = [], array $headers = []): array
    {
        return $this->sendRequest($friendlyName, 'GET', $url, $queryStrings, $headers);
    }

    public function post(string $friendlyName, string $url, array $queryStrings = [], array $headers = [], ?string $body = null): array
    {
        return $this->sendRequest($friendlyName, 'POST', $url, $queryStrings, $headers, $body);
    }

    public function postData(string $friendlyName, string $url, array $queryStrings = [], array $headers = [], array $bodyData = []): array
    {
        $body = http_build_query($bodyData, '', '&');

        return $this->post($friendlyName, $url, $queryStrings, $headers, $body);
    }

    private function sendRequest(string $friendlyName, string $method, string $url, array $queryStrings = [], array $headers = [], ?string $body = null): array
    {
        $queryStringsFlat = http_build_query($queryStrings, '', '&');
        $urlWithQueryStrings = sprintf('%s?%s', $url, $queryStringsFlat);
        $request = new Request($method, $urlWithQueryStrings, $headers, $body);

        try {
            $response = $this->guzzle->send($request);
        } catch (ConnectException $exception) {
            $message = sprintf('Could not connect to %s', $friendlyName);
            throw new UserFriendlyException($message, 0, $exception);
        } catch (BadResponseException $exception) {
            if ($exception->getResponse() instanceof ResponseInterface) {
                $errorResponse = $exception->getResponse();
                $errorBody = $errorResponse->getBody();
                $errorContents = $errorBody->getContents();
                try {
                    $errorData = json_decode($errorContents, true, 512, JSON_THROW_ON_ERROR);
                    $errorMessage = $this->jsonEndpointErrorResponseParser->getFriendlyErrorFromBadResponseJsonData($errorResponse, $errorData);
                } catch (JsonException $exception) {
                    $errorMessage = $this->jsonEndpointErrorResponseParser->getFriendlyErrorFromBadResponse($errorResponse);
                }
                throw new UserFriendlyException($errorMessage, 0, $exception);
            } else {
                $message = sprintf('An unhandled error occurred connecting and retrieving data from %s', $friendlyName);
                throw new UserFriendlyException($message, 0, $exception);
            }
        } catch (Throwable $exception) {
            $message = sprintf('An unhandled error occurred connecting and retrieving data from %s', $friendlyName);
            throw new UserFriendlyException($message, 0, $exception);
        }

        $body = $response->getBody();
        $contents = $body->getContents();
        try {
            $data = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException $exception) {
            $message = sprintf('Could not decode JSON from %s', $friendlyName);
            throw new UserFriendlyException($message, 0, $exception);
        }

        return $data;
    }
}
