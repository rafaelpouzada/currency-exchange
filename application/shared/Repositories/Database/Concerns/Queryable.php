<?php

namespace Shared\Repositories\Database\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Shared\Repositories\Database\SimpleQueryHandler;

trait Queryable
{
    /**
     * Create a new simple query handler
     *
     * @param Builder $queryBuilder
     * @return SimpleQueryHandler
     */
    protected function newSimpleQueryHandler(Builder $queryBuilder): SimpleQueryHandler
    {
        return new SimpleQueryHandler($queryBuilder);
    }

    /**
     * Apply the query
     *
     * @param Builder $queryBuilder
     * @param array   $query
     * @return void
     */
    public function applyQuery(Builder $queryBuilder, array $query): void
    {
        $this->newSimpleQueryHandler($queryBuilder)->applyQuery($query);
    }
}
