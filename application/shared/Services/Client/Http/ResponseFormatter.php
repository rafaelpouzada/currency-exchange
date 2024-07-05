<?php

namespace Shared\Services\Client\Http;

use Shared\Services\Client\Contracts\{Http\IResponseFormatter,};
use Shared\Services\Client\Contracts\Http\IResponse;
use Shared\Entities\Entity;
use Shared\Services\Pagination\{Contracts\IPaginator, Paginator};
use Shared\Support\{Collection, Contracts\ICollection};
use RuntimeException;

/**
 * @phpstan-import-type CollectionClass from IResponseFormatter
 * @phpstan-import-type PaginatorClass from IResponseFormatter
 * @phpstan-type        Options array{collection:CollectionClass,paginator:PaginatorClass}
 */
class ResponseFormatter implements IResponseFormatter
{
    /**
     * The response that contains the data to format.
     *
     * @var IResponse $response
     */
    protected IResponse $response;

    /**
     * The entity class.
     *
     * @var class-string<Entity> $entityClass
     */
    protected string $entityClass;

    /**
     * The collection class.
     *
     * @var class-string<ICollection> $collectionClass
     */
    protected $collectionClass = Collection::class;

    /**
     * The paginator class.
     *
     * @var class-string<IPaginator> $paginatorClass
     */
    protected $paginatorClass = Paginator::class;

    /**
     * @inheritDoc
     */
    public function __construct(IResponse $response, string $entityClass, array $options = [])
    {
        $this->response    = $response;
        $this->entityClass = $entityClass;

        if (isset($options['collection']) && is_a($options['collection'], ICollection::class, true)) {
            $this->collectionClass = $options['collection'];
        }

        if (isset($options['paginator']) && is_a($options['paginator'], IPaginator::class, true)) {
            $this->paginatorClass = $options['paginator'];
        }
    }

    /**
     * Returns the current options.
     *
     * @return array
     */
    protected function getCurrentOptions(): array
    {
        return [
            'collection' => $this->collectionClass,
            'paginator'  => $this->paginatorClass,
        ];
    }

    /**
     * @inheritDoc
     */
    public function withPaginator(string $paginator): IResponseFormatter
    {
        $options = $this->getCurrentOptions();
        $options['paginator'] = $paginator;

        return new static($this->response, $this->entityClass, $options);
    }

    /**
     * @inheritDoc
     */
    public function withCollection(string $collection): IResponseFormatter
    {
        $options = $this->getCurrentOptions();
        $options['collection'] = $collection;

        return new static($this->response, $this->entityClass, $options);
    }

    /**
     * @inheritDoc
     */
    public function withEntity(string $entity): IResponseFormatter
    {
        return new static($this->response, $entity, $this->getCurrentOptions());
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function toEntity(): ?Entity
    {
        $contents   = $this->response->getContents();
        $attributes = $contents['data'] ?? $contents;

        if (empty($attributes)) {
            return null;
        }

        return $this->makeEntity($attributes);
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function toCollection(): ICollection
    {
        $contents = $this->response->getContents();
        $items    = $contents['data'] ?? [];

        return $this->makeCollection($items);
    }

    /**
     * @inheritDoc
     * @throws RuntimeException
     */
    public function toPaginator(): IPaginator
    {
        $contents = $this->response->getContents();
        $items    = $contents['data'] ?? [];
        $paging   = $contents['paging'] ?? [];

        return $this->makePaginator($this->makeCollection($items), $paging);
    }

    /**
     * Makes a new entity instance.
     *
     * @param array $attributes
     * @return Entity
     * @throws RuntimeException
     */
    private function makeEntity(array $attributes): Entity
    {
        if (!is_a($this->entityClass, Entity::class, true)) {
            throw new RuntimeException('The option["entity"] must be an instance of Entity');
        }

        /** @var Entity $entity */
        $entity = new $this->entityClass($attributes);

        return $entity;
    }

    /**
     * Makes a new collection instance.
     *
     * @param mixed $items
     * @return ICollection
     * @throws RuntimeException
     */
    private function makeCollection(mixed $items): ICollection
    {
        if (!is_array($items)) {
            throw new RuntimeException('The collection\'s items should be array');
        }

        if (!is_a($this->collectionClass, ICollection::class, true)) {
            throw new RuntimeException('The option["collection"] must be an instance of ICollection');
        }

        $entities = array_map(function ($attributes) {
            return $this->makeEntity($attributes);
        }, $items);

        return new $this->collectionClass($entities);
    }

    /**
     * Makes a new paginator instance.
     *
     * @param ICollection $items
     * @param array $paging
     * @return IPaginator
     * @throws RuntimeException
     */
    private function makePaginator(ICollection $items, array $paging): IPaginator
    {
        if (!is_a($this->paginatorClass, IPaginator::class, true)) {
            throw new RuntimeException('The option["paginator"] must be an instance of IPaginator');
        }

        return new $this->paginatorClass($items, $paging);
    }
}
