<?php

namespace Shared\Http\Request\Filtering;

use Illuminate\Http\Request;
use Shared\Http\Request\Contracts\Filtering\IFilter;

class Filter implements IFilter
{
    /**
     * Lista de filtros utilizados.
     *
     * @var array $filters
    */
    protected array $filters = [];

    /**
     * Filter constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fill($request->input('filter'));
    }

    /**
     * Preenche o filtro com os campos fornecidos.
     *
     * @param array|string|null $fields
     * @return void
     */
    public function fill(array|string|null $fields = []): void
    {
        if (is_array($fields)) {
            $this->filters = $fields;
        }
    }

    /**
     * {@inheritDoc}
    */
    public function toArray(): array
    {
        return $this->filters;
    }
}
