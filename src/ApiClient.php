<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use ChristianBrown\ApiClient\Transformer\JsonToArrayTransformer;
use ChristianBrown\ApiClient\Transformer\XmlToArrayTransformer;
use GuzzleHttp\Client;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ApiClient
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
    public function getApiRequestSenderForJson(): ApiRequestSenderInterface
    {
        return $this->container->get('christianbrown.api_client.api_request_sender.json');
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getApiRequestSenderForXml(): ApiRequestSenderInterface
    {
        return $this->container->get('christianbrown.api_client.api_request_sender.xml');
    }

    private function init(): void
    {
        $this->container->register('guzzle_http.client', Client::class);

        $this->container->register('christianbrown.api_client.transformer.json_to_array_transformer', JsonToArrayTransformer::class);

        $this->container->register('christianbrown.api_client.transformer.xml_to_array_transformer', XmlToArrayTransformer::class)
            ->setArguments(
                [
                    $this->container->getDefinition('christianbrown.api_client.transformer.json_to_array_transformer'),
                ]
            );

        $this->container->register('christianbrown.api_client.api_request_sender.json', ApiRequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition('guzzle_http.client'),
                    $this->container->getDefinition('christianbrown.api_client.transformer.json_to_array_transformer'),
                ]
            );

        $this->container->register('christianbrown.api_client.api_request_sender.xml', ApiRequestSender::class)
            ->setArguments(
                [
                    $this->container->getDefinition('guzzle_http.client'),
                    $this->container->getDefinition('christianbrown.api_client.transformer.xml_to_array_transformer'),
                ]
            );
    }
}
