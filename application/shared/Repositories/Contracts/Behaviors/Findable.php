<?php

namespace Shared\Repositories\Contracts\Behaviors;

use Shared\Entities\Entity;

interface Findable
{
    /**
     * Return the record with the given id.
     *
     * @param int $id
     * @return Entity|null
     */
    public function find(int $id): ?Entity;
}
