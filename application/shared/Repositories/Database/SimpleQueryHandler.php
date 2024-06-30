<?php

namespace Shared\Repositories\Database;

use Illuminate\Database\Eloquent\Builder;
use Shared\Entities\Support\Str;

class SimpleQueryHandler
{
    use Concerns\Filterable;

    protected Builder $queryBuilder;

    /**
     * @param Builder $queryBuilder
     */
    public function __construct(Builder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * Apply the query
     *
     * @param array   $query
     * @return void
     */
    public function applyQuery(array $query): void
    {
        $filter     = $query['filter']     ?? null;
        $sorter     = $query['sorting']    ?? null;
        $attributes = $query['attributes'] ?? ['*'];
        $with       = $query['with']       ?? null;

        if (is_array($with)) {
            $this->queryBuilder->with($query['with']);
        }

        $this->applyFilters($filter);

        if (is_array($sorter)) {
            foreach ($sorter as $sort) {
                $field     = $sort['field']     ?? null;
                $direction = $sort['direction'] ?? 'ASC';

                if ($field) {
                    $this->queryBuilder->orderBy($field, $direction);
                }
            }
        }

        if (is_array($attributes) && count($attributes) > 0) {
            $this->queryBuilder->select($attributes);
        }
    }

    /**
     * Apply the filters
     *
     * @param mixed $filter
     * @return void
     */
    protected function applyFilters(mixed $filter): void
    {
        if (!is_array($filter) || count($filter) === 0) {
            return;
        }

        foreach ($filter as $key => $value) {
            $column    = $this->getColumn($key);
            $operator  = $this->getOperator($key, $value);

            $this->filterData($this->queryBuilder, $column, $operator, $value);
        }
    }

    /**
     * Return the column
     *
     * @param string $key
     * @return string
     */
    protected function getColumn(string $key): string
    {
        $pattern = '/^([a-zA-Z_]+)[<>!=]+$/';
        return preg_replace($pattern, '$1', $key);
    }

    /**
     * Return the operator
     *
     * @param string $key
     * @param mixed $value
     * @return string
     */
    protected function getOperator(string $key, mixed $value): string
    {
        if (is_string($value) && str_contains($value, ',')) {
            $value = explode(',', $value);
        }

        if ($this->isValueDifferent($key)) {
            if (is_array($value)) {
                return 'NotIn';
            }
            return '!=';
        }

        $operators = [
            '>',
            '>=',
            '<',
            '<=',
        ];

        foreach ($operators as $operator) {
            if (str_ends_with($key, $operator)) {
                return $operator;
            }
        }

        if ($this->isValueLike($value)) {
            return 'Like';
        }

        return is_array($value) ? 'in' : '=';
    }

    /**
     * Verify if the value is different
     *
     * @param string $key
     * @return boolean
     */
    protected function isValueDifferent(string $key): bool
    {
        return str_ends_with($key, '!=') || str_ends_with($key, '<>');
    }

    /**
     * Verify if the value is like
     *
     * @param mixed $value
     * @return boolean
     */
    protected function isValueLike(mixed $value): bool
    {
        if (is_array($value)) {
            return false;
        }

        return (bool)preg_match('/(^%)|(%$)/', $value);
    }

    /**
     * Apply the filter data
     *
     * @param Builder   $queryBuilder
     * @param string    $column
     * @param string    $operator
     * @param mixed     $value
     * @return void
     */
    protected function filterData(Builder $queryBuilder, string $column, string $operator, mixed $value): void
    {
        // Permite customizar a aplicação da query para a coluna
        $method  = Str::camel("apply_{$column}_query");
        if (method_exists($this, $method)) {
            $this->$method($operator, $value);
            return;
        }

        $this->applyFilterQuery($queryBuilder, $column, $operator, $value);
    }

    /**
     * Apply the filter query
     *
     * @param Builder   $queryBuilder
     * @param string    $column
     * @param string    $operator
     * @param mixed     $value
     * @return void
     */
    protected function applyFilterQuery(Builder $queryBuilder, string $column, string $operator, mixed $value): void
    {
        $method  = Str::camel("apply_{$operator}_query");
        if (method_exists($this, $method)) {
            $this->$method($queryBuilder, $column, $value);
            return;
        }

        $queryBuilder->where($column, $operator, $value);
    }
}
