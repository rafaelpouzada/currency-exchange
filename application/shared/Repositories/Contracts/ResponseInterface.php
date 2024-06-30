<?php

namespace Shared\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Shared\Entities\Entity;

interface ResponseInterface
{
    /**
     * ResultTransformer constructor.
     *
     * @param mixed $content
     * @param class-string<Entity> $entityClass
     */
    public function __construct(mixed $content, string $entityClass);

    /**
     * Returns the content of the response.
     *
     * @return mixed
    */
    public function getContent(): mixed;

    /**
     * Return the entity class.
     *
     * @param class-string<Entity> $entityClass
     * @return static
     */
    public function withEntity(string $entityClass): static;

    /**
     * Transform the content into a paginator.
     *
     * @return LengthAwarePaginator
     */
    public function toPaginator(): LengthAwarePaginator;

    /**
     * Transform the content into a collection.
     *
     * @return Collection
    */
    public function toCollection(): Collection;

    /**
     * Transform the content into an entity.
     *
     * @return Entity|null
     */
    public function toEntity(): ?Entity;
}
