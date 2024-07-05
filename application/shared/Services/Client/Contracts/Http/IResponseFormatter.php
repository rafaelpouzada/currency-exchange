<?php

namespace Shared\Services\Client\Contracts\Http;

use Shared\Entities\Entity;
use Shared\Services\Pagination\Contracts\IPaginator;
use Shared\Support\Contracts\ICollection;

/**
 * @phpstan-type CollectionClass class-string<ICollection>
 * @phpstan-type PaginatorClass class-string<IPaginator>
 * @phpstan-type Options array{collection?:CollectionClass,paginator?:PaginatorClass}
 */
interface IResponseFormatter
{
    /**
     * IResponseFormatter's constructor.
     *
     * @param IResponse              $response
     * @param class-string<Entity>   $entityClass
     * @param Options                $options
     */
    public function __construct(IResponse $response, string $entityClass, array $options = []);

    /**
     * Creates a new response instance with the given entity class.
     *
     * @param class-string<IPaginator> $paginator
     *
     * @return IResponseFormatter
     */
    public function withPaginator(string $paginator): IResponseFormatter;

    /**
     * Creates a new response instance with the given entity class.
     *
     * @param class-string<ICollection> $collection
     *
     * @return IResponseFormatter
     */
    public function withCollection(string $collection): IResponseFormatter;

    /**
     * Creates a new response instance with the given entity class.
     *
     * @param class-string<Entity> $entity
     *
     * @return IResponseFormatter
     */
    public function withEntity(string $entity): IResponseFormatter;

    /**
     * Transforma os dados fornecidos em dados paginados.
     *
     * @return IPaginator
     */
    public function toPaginator(): IPaginator;

    /**
     * Transforma os itens em uma coleção de entidades.
     *
     * @return ICollection
     */
    public function toCollection(): ICollection;

    /**
     * Transforma o item fornecido em uma entidade.
     *
     * @return Entity|null
     */
    public function toEntity(): ?Entity;
}
