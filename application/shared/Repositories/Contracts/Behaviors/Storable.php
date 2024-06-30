<?php

namespace Shared\Repositories\Contracts\Behaviors;

use ArrayAccess;
use Shared\Entities\Entity;

interface Storable
{
    /**
     * Create a new record.
     *
     * @param ArrayAccess|array $attributes
     * @return Entity
     */
    public function store(ArrayAccess|array $attributes): Entity;
}
