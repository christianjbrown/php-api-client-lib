<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Exception\Request\ConnectException;
use ChristianBrown\ApiClient\Exception\Response\BadResponseException;
use ChristianBrown\ApiClient\Exception\Response\TooManyRedirectsException;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\BadResponseException as GuzzleBadResponseException;
use GuzzleHttp\Exception\ConnectException as GuzzleConnectException;
use GuzzleHttp\Exception\TooManyRedirectsException as GuzzleTooManyRedirectsException;
use GuzzleHttp\Psr7\Request;

use function http_build_query;
use function sprintf;

final class ApiRequestSender implements ApiRequestSenderInterface
{
    private ClientInterface $guzzle;

    public function __construct(ClientInterface $guzzle)
    {
        $this->guzzle = $guzzle;
    }

    /**
     * @throws ConnectException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    public function get(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = []): string
    {
        return $this->sendRequest(self::METHOD_GET, $requestUrl, $requestQueryStrings, $requestHeaders);
    }

    /**
     * @throws ConnectException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    public function post(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?string $requestBody = null): string
    {
        return $this->sendRequest(self::METHOD_POST, $requestUrl, $requestQueryStrings, $requestHeaders, $requestBody);
    }

    /**
     * @throws ConnectException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    public function postData(string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], array $requestBodyData = []): string
    {
        $requestBody = http_build_query($requestBodyData, '', '&');
        $data = $this->post($requestUrl, $requestQueryStrings, $requestHeaders, $requestBody);

        return $data;
    }

    /**
     * @throws ConnectException
     * @throws BadResponseException
     * @throws TooManyRedirectsException
     */
    private function sendRequest(string $method, string $requestUrl, array $requestQueryStrings = [], array $requestHeaders = [], ?string $requestBody = null): string
    {
        $finalUrl = $requestUrl;
        if (!empty($requestQueryStrings)) {
            $requestQueryStringsFlat = http_build_query($requestQueryStrings, '', '&');
            $finalUrl = sprintf('%s?%s', $requestUrl, $requestQueryStringsFlat);
        }
        $request = new Request($method, $finalUrl, $requestHeaders, $requestBody);

        try {
            $response = $this->guzzle->send($request);
        } catch (GuzzleConnectException $exception) {
            throw new ConnectException($request, $exception);
        } catch (GuzzleBadResponseException $exception) {
            throw new BadResponseException($request, $exception);
        } catch (GuzzleTooManyRedirectsException $exception) {
            throw new TooManyRedirectsException($request, $exception);
        }

        $requestBody = $response->getBody();
        $contents = $requestBody->getContents();

        return $contents;
    }
}
