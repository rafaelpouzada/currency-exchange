<?php

namespace Shared\Services\Client;

use Shared\Services\Client\Http\Request;
use Shared\Services\Client\Contracts\{IConfig, IService};
use Shared\Services\Client\Contracts\Http\IRequest;
use Shared\Services\Client\Contracts\IClient;
use GuzzleHttp\{Client as HttpClient, ClientInterface, Handler\CurlHandler, HandlerStack};

class Client implements IClient
{
    /**
     * The client's config storage.
     *
     * @var IConfig $config
     */
    protected IConfig $config;

    /**
     * @var IService[] $services
     */
    protected array $services = [];

    /**
     * The request's instance
     *
     * @var IRequest|null $request
     */
    protected ?IRequest $request;

    /**
     * Middlewares extras que serão anexados nas requisições.
     *
     * @var callable[] $middlewares
     */
    protected array $middlewares = [];

    /**
     * @inheritDoc
     */
    public function __construct(IConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @inheritDoc
     */
    public function getConfig(): IConfig
    {
        return $this->config;
    }

    /**
     * @inheritDoc
     */
    public function addMiddleware(callable $middleware): void
    {
        $this->middlewares[] = $middleware;
    }

    /**
     * {@inheritDoc}
     */
    public function getRequest(): IRequest
    {
        if (!isset($this->request)) {
            $this->request = new Request($this->createHttpClient());
        }

        return $this->request;
    }

    /**
     * Makes a fresh http instance.
     *
     * @return ClientInterface
     */
    protected function createHttpClient(): ClientInterface
    {
        $stack = new HandlerStack();

        $stack->setHandler(new CurlHandler());

        foreach ($this->middlewares as $middleware) {
            $stack->push($middleware);
        }

        $options = [
            'base_uri'   => $this->getConfig()->getApiUrl(),
            'exceptions' => true,
            'handler'    => $stack,
            'timeout'    => 60
        ];

        return new HttpClient($options);
    }
}
