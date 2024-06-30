<?php

namespace Shared\Repositories\Contracts\Behaviors;

interface Deletable
{
    /**
     * Delete the record with the given id.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
