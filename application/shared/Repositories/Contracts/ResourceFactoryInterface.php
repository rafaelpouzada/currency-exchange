<?php

namespace Shared\Repositories\Contracts;

use Shared\Entities\Entity;

interface ResourceFactoryInterface
{
    /**
     * Create the resource instance.
     *
     * @param mixed                $resource
     * @param class-string<Entity> $entityClass
     * @return ResourceInterface
     */
    public function makeResource(mixed $resource, string $entityClass): ResourceInterface;
}
