<?php

namespace Shared\Repositories\Concerns;

use Shared\Repositories\Contracts\ResourceInterface;

/**
 * @method ResourceInterface getResource()
 *
 * @see \Shared\Repositories\Contracts\Behaviors\Deletable
 */
trait CanDelete
{
    /**
     * Delete a record by its ID.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this
            ->getResource()
            ->delete($id)
            ->getContent();
    }
}
