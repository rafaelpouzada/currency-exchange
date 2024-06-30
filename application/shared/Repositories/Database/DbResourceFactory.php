<?php

namespace Shared\Repositories\Database;

use Shared\Repositories\Contracts\{ResourceFactoryInterface};
use Shared\Repositories\Contracts\ResourceInterface;

class DbResourceFactory implements ResourceFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function makeResource($resource, string $entityClass): ResourceInterface
    {
        return new DbResource($resource, $entityClass);
    }
}
