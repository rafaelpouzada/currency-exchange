<?php

namespace Shared\Services\Client\Contracts\Http;

use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Support\Arrayable;
use Psr\Http\Message\RequestInterface;

interface IRequest
{
    /**
     * IRequest constructor.
     *
     * @param ClientInterface $httpClient
     */
    public function __construct(ClientInterface $httpClient);

    /**
     * Returns the authorized http client.
     *
     * @return ClientInterface
     */
    public function getHttpClient(): ClientInterface;

    /**
     * Runs the given request.
     *
     * @param  RequestInterface $request
     * @param  array            $queries
     * @return IResponse
     */
    public function execute(RequestInterface $request, array $queries = []): IResponse;

    /**
     * Make requests with the GET method.
     *
     * @param  string $uri
     * @param  array  $queries
     * @param  array  $headers
     * @return IResponse
     */
    public function get(string $uri, array $queries = [], array $headers = []): IResponse;

    /**
     * Make requests with the POST method.
     *
     * @param  string                $uri
     * @param  array                 $queries
     * @param  array                 $headers
     * @param array|Arrayable|null $body
     * @return IResponse
     */
    public function post(string $uri, array $queries = [], array $headers = [], array|Arrayable $body = null): IResponse;

    /**
     * Make requests with the UPDATE method.
     *
     * @param  string                $uri
     * @param  array                 $queries
     * @param  array                 $headers
     * @param array|Arrayable|null $body
     * @return IResponse
     */
    public function put(string $uri, array $queries = [], array $headers = [], array|Arrayable $body = null): IResponse;

    /**
     * Make requests with the DELETE method.
     *
     * @param  string $uri
     * @param  array  $queries
     * @param  array  $headers
     * @return IResponse
     */
    public function delete(string $uri, array $queries = [], array $headers = []): IResponse;
}
