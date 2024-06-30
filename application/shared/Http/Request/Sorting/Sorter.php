<?php

namespace Shared\Http\Request\Sorting;

use Illuminate\Http\Request;
use Shared\Http\Request\Contracts\Sorting\ISorter;

class Sorter implements ISorter
{
    /**
     * Lista com os campos de ordenação.
     *
     * @var array $sort
     */
    protected array $sort = [];

    /**
     * Sorter constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->fill($request->input('sort'));
    }

    /**
     * Preenche a ordenação com os campos fornecidos.
     *
     * @param array|string|null $fields
     * @return void
     */
    public function fill(array|string|null $fields): void
    {
        if (is_string($fields)) {
            $fields = explode(',', trim($fields));
        }

        if (empty($fields)) {
            return;
        }

        $this->sort = array_map(static function ($field) {
            $direction = str_starts_with($field, '-') ? 'desc' : 'asc';
            $field     = preg_replace('/^[-+]/', '', $field);
            return new Sort($field, $direction);
        }, $fields);
    }

    /**
     * {@inheritDoc}
     */
    public function getSorting(): array
    {
        return $this->sort;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_map(static function (Sort $sort) {
            return [
                'field'     => $sort->getField(),
                'direction' => $sort->getDirection()
            ];
        }, $this->sort);
    }
}
