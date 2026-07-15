<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Transformer\ArrayToJsonTransformer;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformer;
use ChristianBrown\ApiClient\Transformer\StringToXmlDocTransformer;
use ChristianBrown\ApiClient\Transformer\XmlDocToStringTransformer;
use GuzzleHttp\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ApiClient implements ApiClientInterface
{
    private ContainerBuilder $container;

    public function __construct()
    {
        $this->container = new ContainerBuilder();
        $this->init();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getApiRequestSender(): ApiRequestSenderInterface
    {
        /**
         * @var ApiRequestSenderInterface $service
         */
        $service = $this->container->get(self::SERVICE_API_REQUEST_SENDER);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getJsonApiRequestSender(): JsonApiRequestSenderInterface
    {
        /**
         * @var JsonApiRequestSenderInterface $service
         */
        $service = $this->container->get(self::SERVICE_JSON_API_REQUEST_SENDER);

        return $service;
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getXmlApiRequestSender(): XmlApiRequestSenderInterface
    {
        /**
         * @var XmlApiRequestSenderInterface $service
         */
        $service = $this->container->get(self::SERVICE_XML_API_REQUEST_SENDER);

        return $service;
    }

    private function init(): void
    {
        $this->container->register(self::SERVICE_GUZZLE_CLIENT, Client::class);

        $this->container->register(self::SERVICE_TRANSFORMER_ARRAY_TO_JSON, ArrayToJsonTransformer::class);
        $this->container->register(self::SERVICE_TRANSFORMER_JSON_TO_ARRAY, JsonToArrayTransformer::class);
        $this->container->register(self::SERVICE_TRANSFORMER_STRING_TO_XML_DOC, StringToXmlDocTransformer::class);
        $this->container->register(self::SERVICE_TRANSFORMER_XML_DOC_TO_STRING, XmlDocToStringTransformer::class);

        $this->container->register(self::SERVICE_API_REQUEST_SENDER, ApiRequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_GUZZLE_CLIENT),
                ]
            );

        $this->container->register(self::SERVICE_JSON_API_REQUEST_SENDER, JsonApiRequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_TRANSFORMER_JSON_TO_ARRAY),
                    $this->container->getDefinition(self::SERVICE_TRANSFORMER_ARRAY_TO_JSON),
                ]
            );

        $this->container->register(self::SERVICE_XML_API_REQUEST_SENDER, XmlApiRequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition(self::SERVICE_API_REQUEST_SENDER),
                    $this->container->getDefinition(self::SERVICE_TRANSFORMER_STRING_TO_XML_DOC),
                    $this->container->getDefinition(self::SERVICE_TRANSFORMER_XML_DOC_TO_STRING),
                ]
            );
    }
}
