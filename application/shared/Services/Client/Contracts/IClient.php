<?php

namespace Shared\Services\Client\Contracts;

use Shared\Services\Client\Contracts\Auth\IStorage;
use Shared\Services\Client\Contracts\Http\IRequest;

interface IClient
{
    /**
     * This client is currently in beta version.
     *
     * @var string
     */
    public const VERSION = '0.1.0';

    /**
     * IClient constructor.
     *
     * @param IConfig $config
     */
    public function __construct(IConfig $config);

    /**
     * Returns the config instance.
     *
     * @return IConfig
     */
    public function getConfig(): IConfig;

    /**
     * Adiciona o middleware fornecido nas requisições.
     *
     * @param callable $middleware
     */
    public function addMiddleware(callable $middleware): void;

    /**
     * Retrieves the http client.
     *
     * @return IRequest
     */
    public function getRequest(): IRequest;
}
