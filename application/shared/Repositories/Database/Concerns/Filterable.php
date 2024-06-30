<?php

namespace Shared\Repositories\Database\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply the not in filter
     *
     * @param Builder   $queryBuilder
     * @param string    $column
     * @param array     $values
     * @return void
     */
    protected function applyNotInQuery(Builder $queryBuilder, string $column, array $values): void
    {
        $queryBuilder->whereNotIn($column, $values);
    }

    /**
     * Apply the in filter
     *
     * @param Builder   $queryBuilder
     * @param string    $column
     * @param array     $values
     * @return void
     */
    protected function applyInQuery(Builder $queryBuilder, string $column, array $values): void
    {
        $queryBuilder->whereIn($column, $values);
    }

    /**
     * Apply the like filter
     *
     * @param Builder   $queryBuilder
     * @param string    $column
     * @param string    $value
     * @return void
     */
    public function applyLikeQuery(Builder $queryBuilder, string $column, string $value): void
    {
        if (str_starts_with($value, '%') || str_ends_with($value, '%')) {
            $queryBuilder->where($column, 'LIKE', $value);
            return;
        }

        $queryBuilder->where($column, 'LIKE', '%' . $value . '%');
    }
}
