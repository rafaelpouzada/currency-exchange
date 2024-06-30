<?php

namespace Shared\Repositories\Contracts\Behaviors;

use ArrayAccess;
use Shared\Entities\Entity;

interface Updatable
{
    /**
     * Update the record with the given id.
     *
     * @param int                $id
     * @param ArrayAccess|array $attributes
     * @return Entity
     */
    public function update(int $id, ArrayAccess|array $attributes): Entity;
}
