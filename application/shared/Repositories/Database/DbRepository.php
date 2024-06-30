<?php

namespace Shared\Repositories\Database;

use Shared\Entities\Entity;
use Shared\Repositories\Database\DbResourceFactory as ResourceFactoryInterface;
use Shared\Repositories\Contracts\{RepositoryInterface, ResourceInterface};

abstract class DbRepository implements RepositoryInterface
{
    /**
     * Resource for reading the service.
     *
     * @var ResourceInterface|null $resource
     */
    protected ?ResourceInterface $resource;

    /**
     * Factory used to build the resource.
     *
     * @var ResourceFactoryInterface $resourceFactory
     */
    protected ResourceFactoryInterface $resourceFactory;

    /**
     * Repository constructor.
     *
     * @param ResourceFactoryInterface $resourceFactory
     */
    public function __construct(ResourceFactoryInterface $resourceFactory)
    {
        $this->resourceFactory = $resourceFactory;
    }

    /**
     * @inheritDoc
     */
    public function getResource(): ResourceInterface
    {
        $modelClass  = $this->getResourceClass();
        $entityClass = $this->getEntityClass();

        return $this->resourceFactory->makeResource(new $modelClass(), $entityClass);
    }

    /**
     * Returns the model class.
     *
     * @return string
     */
    abstract public function getResourceClass(): string;

    /**
     * Returns the entity class.
     *
     * @return class-string<Entity>
     */
    abstract public function getEntityClass(): string;
}
