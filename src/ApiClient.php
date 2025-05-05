<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Transformer\ArrayToJsonTransformer;
use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformer;
use ChristianBrown\ApiClient\Transformer\StringToXmlDocTransformer;
use ChristianBrown\ApiClient\Transformer\XmlDocToStringTransformer;
use GuzzleHttp\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ApiClient implements ApiClientInterface
{
    private ContainerInterface $container;

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
        return $this->container->get('christianbrown.api_client.api_request_sender');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getJsonApiRequestSender(): JsonApiRequestSenderInterface
    {
        return $this->container->get('christianbrown.api_client.json_api_request_sender');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getXmlApiRequestSender(): XmlApiRequestSenderInterface
    {
        return $this->container->get('christianbrown.api_client.xml_api_request_sender');
    }

    private function init(): void
    {
        $this->container->register('guzzle_http.client', Client::class);

        $this->container->register('christianbrown.api_client.transformer.array_to_json_transformer', ArrayToJsonTransformer::class);
        $this->container->register('christianbrown.api_client.transformer.json_to_array_transformer', JsonToArrayTransformer::class);
        $this->container->register('christianbrown.api_client.transformer.string_to_xml_doc_transformer', StringToXmlDocTransformer::class);
        $this->container->register('christianbrown.api_client.transformer.xml_doc_to_string_transformer', XmlDocToStringTransformer::class);

        $this->container->register('christianbrown.api_client.api_request_sender', ApiRequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition('guzzle_http.client'),
                ]
            );

        $this->container->register('christianbrown.api_client.json_api_request_sender', JsonApiRequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition('christianbrown.api_client.api_request_sender'),
                    $this->container->getDefinition('christianbrown.api_client.transformer.json_to_array_transformer'),
                    $this->container->getDefinition('christianbrown.api_client.transformer.array_to_json_transformer'),
                ]
            );

        $this->container->register('christianbrown.api_client.xml_api_request_sender', XmlApiRequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition('christianbrown.api_client.api_request_sender'),
                    $this->container->getDefinition('christianbrown.api_client.transformer.string_to_xml_doc_transformer'),
                    $this->container->getDefinition('christianbrown.api_client.transformer.xml_doc_to_string_transformer'),
                ]
            );
    }
}
