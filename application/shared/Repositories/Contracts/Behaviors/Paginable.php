<?php

namespace Shared\Repositories\Contracts\Behaviors;

use Illuminate\Pagination\LengthAwarePaginator;

interface Paginable
{
    /**
     * Return a paginated list of records.
     *
     * @param array $query
     * @return LengthAwarePaginator
     */
    public function paginate(array $query = []): LengthAwarePaginator;
}
