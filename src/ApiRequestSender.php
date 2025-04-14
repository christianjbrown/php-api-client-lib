<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Exception\Parse\ParseJsonException;
use ChristianBrown\ApiClient\Exception\Parse\ParseXmlException;
use ChristianBrown\ApiClient\Exception\Request\ConnectException;
use ChristianBrown\ApiClient\Exception\Response\ResponseException;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsException;
use ChristianBrown\ApiClient\Transformer\DataToArrayTransformerInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\TooManyRedirectsException as GuzzleTooManyRedirectsException;
use GuzzleHttp\Psr7\Request;

use function http_build_query;
use function sprintf;

final class ApiRequestSender implements ApiRequestSenderInterface
{
    private ClientInterface $guzzle;
    private DataToArrayTransformerInterface $responseTransformer;

    public function __construct(ClientInterface $guzzle, DataToArrayTransformerInterface $responseTransformer)
    {
        $this->guzzle = $guzzle;
        $this->responseTransformer = $responseTransformer;
    }

    /**
     * @throws ConnectException
     * @throws GuzzleException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    public function get(string $url, array $queryStrings = [], array $headers = []): array
    {
        return $this->sendRequest(self::METHOD_GET, $url, $queryStrings, $headers);
    }

    /**
     * @throws ConnectException
     * @throws GuzzleException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    public function post(string $url, array $queryStrings = [], array $headers = [], ?string $body = null): array
    {
        return $this->sendRequest(self::METHOD_POST, $url, $queryStrings, $headers, $body);
    }

    /**
     * @throws ConnectException
     * @throws GuzzleException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
    public function postData(string $url, array $queryStrings = [], array $headers = [], array $bodyData = []): array
    {
        $body = http_build_query($bodyData, '', '&');
        $data = $this->post($url, $queryStrings, $headers, $body);

        return $data;
    }

    /**
     * @throws ConnectException
     * @throws GuzzleException
     * @throws ParseJsonException
     * @throws ParseXmlException
     * @throws ResponseException
     * @throws TooManyRedirectsException
     */
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
        } catch (GuzzleConnectException $exception) {
            throw new ConnectException($request, $exception);
        } catch (GuzzleBadResponseException $exception) {
            throw new ResponseException($request, $exception);
        } catch (GuzzleTooManyRedirectsException $exception) {
            throw new TooManyRedirectsException($request, $exception);
        }

        $body = $response->getBody();
        $contents = $body->getContents();

        $data = $this->responseTransformer->transform($contents, $request, $response);

        return $data;
    }
}
