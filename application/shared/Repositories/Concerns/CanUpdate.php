<?php

namespace Shared\Repositories\Concerns;

use ArrayAccess;
use Shared\Entities\Entity;
use Illuminate\Contracts\Support\Arrayable;
use Shared\Repositories\Contracts\ResourceInterface;

/**
 * @mixin CanFind
 * @method ResourceInterface getResource()
 *
 * @see \Shared\Repositories\Contracts\Behaviors\Updatable
 */
trait CanUpdate
{
    /**
     * Update the record.
     *
     * @param int                $id
     * @param ArrayAccess|array $attributes
     * @return Entity
     */
    public function update(int $id, ArrayAccess|array $attributes): Entity
    {
        if ($attributes instanceof Arrayable) {
            $attributes = $attributes->toArray();
        }

        $response = $this
            ->getResource()
            ->update($id, $attributes)
            ->getContent();

        if ($response instanceof Entity) {
            return $response;
        }

        return $this->find($id);
    }
}
