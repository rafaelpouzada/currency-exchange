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
 * @see \Shared\Repositories\Contracts\Behaviors\Storable
 */
trait CanStore
{
    /**
     * Create a new record.
     *
     * @param ArrayAccess|array $attributes
     * @return Entity
     */
    public function store(ArrayAccess|array $attributes): Entity
    {
        if ($attributes instanceof Arrayable) {
            $attributes = $attributes->toArray();
        }

        $response = $this
            ->getResource()
            ->store($attributes)
            ->getContent();

        if ($response instanceof Entity) {
            return $response;
        }

        return $this->find($response);
    }
}
