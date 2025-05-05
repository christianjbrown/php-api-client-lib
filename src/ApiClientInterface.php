<?php

declare(strict_types=1);

namespace ChristianBrown\ApiClient;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

interface ApiClientInterface
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getApiRequestSender(): ApiRequestSenderInterface;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getJsonApiRequestSender(): JsonApiRequestSenderInterface;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function getXmlApiRequestSender(): XmlApiRequestSenderInterface;
}
