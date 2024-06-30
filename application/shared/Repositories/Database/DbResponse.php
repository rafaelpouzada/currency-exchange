<?php

namespace Shared\Repositories\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Shared\Entities\Entity;
use Shared\Repositories\Contracts\ResponseInterface;
use Shared\Exceptions\ServerException;

class DbResponse implements ResponseInterface
{
    /**
     * Data returned by the database.
     *
     * @var mixed $content
     */
    protected mixed $content;

    /**
     * Class name of the entity.
     *
     * @var class-string<Entity> $entityClass
     */
    protected string $entityClass;

    /**
     * @inheritDoc
     */
    public function __construct($content, string $entityClass)
    {
        $this->content     = $content;
        $this->entityClass = $entityClass;
    }

    /**
     * @inheritDoc
     */
    public function getContent(): mixed
    {
        return $this->content;
    }

    /**
     * @inheritDoc
     */
    public function withEntity(string $entityClass): static
    {
        return new static($this->getContent(), $entityClass);
    }

    /**
     * @inheritDoc
     * @throws ServerException
     */
    public function toPaginator(): LengthAwarePaginator
    {
        if (!($this->content instanceof LengthAwarePaginator)) {
            throw new ServerException('Response must implement interface ' . LengthAwarePaginator::class);
        }

        return $this->content->setCollection($this->toCollection());
    }

    /**
     * @inheritDoc
     * @throws ServerException
     */
    public function toCollection(): Collection
    {
        $content = $this->content;
        if ($content instanceof LengthAwarePaginator) {
            $content = $content->getCollection();
        }

        if (!($content instanceof Collection)) {
            throw new ServerException('Response must implement interface ' . Collection::class);
        }

        $items = $content->map(function (Model $model) {
            return $this->makeEntity($this->getEntityAttributes($model));
        });

        return new Collection($items->all());
    }

    /**
     * @inheritDoc
     * @throws ServerException
     */
    public function toEntity(): ?Entity
    {
        if (empty($this->content)) {
            return null;
        }

        if (!($this->content instanceof Model)) {
            throw new ServerException('Response must implement interface ' . Model::class);
        }

        return $this->makeEntity($this->getEntityAttributes($this->content));
    }

    /**
     * Cria uma nova entidade com os atributos fornecidos.
     *
     * @param array $attributes
     * @return Entity
     */
    private function makeEntity(array $attributes): Entity
    {
        return new $this->entityClass($attributes);
    }

    /**
     * Retorna os atributos formatados para a entidade.
     *
     * @param Model $model
     * @return array
     */
    private function getEntityAttributes(Model $model): array
    {
        if (method_exists($model, 'toEntityAttributes')) {
            return $model->toEntityAttributes();
        }

        return $model->toArray();
    }
}
