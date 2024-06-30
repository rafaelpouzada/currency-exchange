<?php

namespace Shared\Repositories\Contracts;

interface RepositoryInterface
{
    /**
     * Return the resource instance.
     *
     * @return ResourceInterface
    */
    public function getResource(): ResourceInterface;
}
