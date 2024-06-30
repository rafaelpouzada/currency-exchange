<?php

namespace Shared\Repositories\Concerns;

use Illuminate\Support\Collection;
use Shared\Repositories\Contracts\ResourceInterface;

/**
 * @method ResourceInterface getResource()
 *
 * @see \Shared\Repositories\Contracts\Behaviors\Listable
 */
trait CanList
{
    /**
     * Return a list of records.
     *
     * @param array $query
     * @return Collection
     */
    public function findAll(array $query = []): Collection
    {
        return $this
            ->getResource()
            ->findAll($query)
            ->toCollection();
    }
}
