<?php

namespace CurrencyConversion\Providers;

use CurrencyConversion\Business\CurrencyConversionCreator\{
    Contracts\ICurrencyConversionCreator,
    CurrencyConversionCreator
};
use CurrencyConversion\Business\CurrencyConversionFinder\{
    Contracts\ICurrencyConversionFinder,
    CurrencyConversionFinder
};
use CurrencyConversion\Repositories\{
    Contracts\ICurrencyConversionRepository,
    CurrencyConversionRepository
};
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Shared\Services\Client\Client;
use Shared\Services\Client\Config;
use Shared\Services\Client\Contracts\IClient;
use Throwable;

class CurrencyConversionProvider extends ServiceProvider
{
    /**
     * Register any application Business.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->bind(ICurrencyConversionFinder::class, CurrencyConversionFinder::class);
        $this->app->bind(ICurrencyConversionCreator::class, CurrencyConversionCreator::class);
        $this->app->bind(ICurrencyConversionRepository::class, CurrencyConversionRepository::class);

        $this->app->bind(IClient::class, function () {
            $apiUrl = config('exchange_rates.api_url');
            $apiKey = config('exchange_rates.api_key');
            $apiUrl = str_replace('{api_key}', $apiKey, $apiUrl);
            $config = new Config([
                'api_url' => $apiUrl
            ]);

            $client = new Client($config);
            $this->addLogMiddleware($client);

            return $client;
        });
    }

    /**
     * Adiciona log nas requisições e respostas realizadas para os aplicativos.
     *
     * @param IClient $client
     * @return void
     */
    protected function addLogMiddleware(IClient $client): void
    {
        $client->addMiddleware(function (callable $handler) {
            return function (RequestInterface $request, array $options) use ($handler) {
                $method = strtoupper($request->getMethod());
                if ($method === 'GET') {
                    return $handler($request, $options);
                }

                $context = $this->getRequestContext($request);
                Log::info('Enviando requisição para a Exchange Rates API', [
                    'type' => 'request',
                    ...$context
                ]);

                return $handler($request, $options)->then(
                    function (ResponseInterface $response) use ($context) {
                        Log::info('Resposta recebida pela aplicação', [
                            ...$context,
                            'type'     => 'response',
                            'status'   => $response->getStatusCode(),
                            'response' => value(static function () use ($response) {
                                try {
                                    return json_decode($response->getBody()->getContents(), true);
                                } catch (Throwable $throwable) {
                                    return null;
                                }
                            })
                        ]);

                        $response->getBody()->rewind();
                        return $response;
                    }
                );
            };
        });
    }

    /**
     * Retorna o contexto da requisição.
     *
     * @param RequestInterface $request
     * @return array
     */
    protected function getRequestContext(RequestInterface $request): array
    {
        $uri    = $request->getUri();
        $params =  value(static function () use ($request) {
            try {
                return json_decode($request->getBody()->getContents(), true);
            } catch (Throwable $throwable) {
                return null;
            }
        });

        $request->getBody()->rewind();
        return [
            'method' => $request->getMethod(),
            'host'   => $uri->getHost(),
            'path'   => $uri->getPath(),
            'query'  => $uri->getQuery(),
            'params' => $params
        ];
    }
}
