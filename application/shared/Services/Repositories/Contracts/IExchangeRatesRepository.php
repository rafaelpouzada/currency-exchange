<?php

namespace Shared\Services\Repositories\Contracts;

use Illuminate\Support\Arr;
use Shared\Entities\Entity;
use Shared\Services\Client\Contracts\Http\{IResponse, IResponseFormatter};
use Shared\Services\Client\Contracts\IClient;
use Shared\Services\Client\Http\ResponseFormatter;

abstract class IExchangeRatesRepository
{
    /**
     * Retorna o client para a API do Prohemo.
     *
     * @return IClient
     */
    public function client(): IClient
    {
        return app(IClient::class);
    }

    /**
     * Returns the envelope used by the entity hydrate.
     *
     * @return class-string<Entity>
     */
    abstract protected function getEntityClass(): string;

    /**
     * Makes a new response formatter instance.
     *
     * @param IResponse $response
     * @return IResponseFormatter
     */
    protected function makeResponseFormatter(IResponse $response): IResponseFormatter
    {
        return new ResponseFormatter($response, $this->getEntityClass());
    }

    /**
     * Retorna os dados de paginação com base na query fornecida.
     *
     * @param array $query
     * @param int   $defaultPageSize
     * @return array
     */
    protected function getPaging(array $query, int $defaultPageSize = 400): array
    {
        $pageNumber = Arr::get($query, 'page.number', 1);
        $pageSize   = Arr::get($query, 'page.size', $defaultPageSize);

        return [
            'pageNum'  => $pageNumber,
            'pageSize' => $pageSize,
        ];
    }
}
