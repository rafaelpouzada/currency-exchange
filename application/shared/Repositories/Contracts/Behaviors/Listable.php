<?php

namespace Shared\Repositories\Contracts\Behaviors;

use Illuminate\Support\Collection;

interface Listable
{
    /**
     * Return a list of records.
     *
     * @param array $query
     * @return Collection
     */
    public function findAll(array $query = []): Collection;
}
