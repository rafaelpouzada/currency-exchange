<?php

namespace Shared\Repositories\Concerns;

use Shared\Entities\Entity;
use Shared\Repositories\Contracts\ResourceInterface;

/**
 * @method ResourceInterface getResource()
 *
 * @see \Shared\Repositories\Contracts\Behaviors\Findable
 */
trait CanFind
{
    /**
     * Find a record by its ID.
     *
     * @param int $id
     * @return Entity|null
     */
    public function find(int $id): ?Entity
    {
        return $this
            ->getResource()
            ->find($id)
            ->toEntity();
    }
}
