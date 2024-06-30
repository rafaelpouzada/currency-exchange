<?php

namespace Shared\Http\Request;

use Illuminate\Http\Request;
use Shared\Http\Request\{
    Filtering\Filter,
    Paging\Pagination,
    Sorting\Sorter
};
use Shared\Http\Request\Contracts\{
    Filtering\IFilter,
    Paging\IPagination,
    Sorting\ISorter
};

class Query
{
    /**
     * @var Request
     */
    protected Request $request;

    /**
     * Request's constructor
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Filtros solicitados na requisição.
     *
     * @var IFilter|null $filter
     */
    protected ?IFilter $filter;

    /**
     * Ordenação solicitada na requisição.
     *
     * @var ISorter|null $sorter
     */
    protected ?ISorter $sorter;

    /**
     * Paginação solicitada na requisição.
     *
     * @var IPagination|null $pagination
     */
    protected ?IPagination $pagination;

    /**
     * Retorna o filtro solicitado na requisição.
     *
     * @return IFilter
     */
    public function getFilter(): IFilter
    {
        if (!isset($this->filter)) {
            $this->filter = new Filter($this->request);
        }
        return $this->filter;
    }

    /**
     * Retorna a ordenação solicitada na requisição.
     *
     * @return ISorter
     */
    public function getSorter(): ISorter
    {
        if (!isset($this->sorter)) {
            $this->sorter = new Sorter($this->request);
        }

        return $this->sorter;
    }

    /**
     * Retorna a paginação solicitada na requisição.
     *
     * @return IPagination
     */
    public function getPagination(): IPagination
    {
        if (!isset($this->pagination)) {
            $this->pagination = new Pagination($this->request);
        }
        return $this->pagination;
    }

    /**
     * Retorna os dados de consulta.
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'filter'  => $this->getFilter()->toArray(),
            'page'    => $this->getPagination()->toArray(),
            'sorting' => $this->getSorter()->toArray(),
            'query'   => $this->request->input('query')
        ];
    }
}
