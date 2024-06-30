<?php

namespace Shared\Repositories\Concerns;

use Illuminate\Pagination\LengthAwarePaginator;
use Shared\Repositories\Contracts\ResourceInterface;

/**
 * @method ResourceInterface getResource()
 *
 * @see \Shared\Repositories\Contracts\Behaviors\Paginable
*/
trait CanPaginate
{
    /**
     * Return a paginated list of records.
     *
     * @param array $query
     * @return LengthAwarePaginator
     */
    public function paginate(array $query = []): LengthAwarePaginator
    {
        return $this
            ->getResource()
            ->paginate($query)
            ->toPaginator();
    }
}
