<?php

namespace Shared\Repositories\Database;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Shared\Repositories\Contracts\{ResponseInterface, ResourceInterface};
use Shared\Exceptions\{ClientException, ServerException};
use Shared\Entities\Entity;
use Shared\Repositories\Database\Concerns\Queryable;
use Throwable;

use function with;

class DbResource implements ResourceInterface
{
    use Queryable;

    /**
     * Model do Laravel.
     *
     * @var Model $resource
     */
    protected Model $resource;

    /**
     * Model to be used.
     *
     * @var class-string<Entity> $entityClass
     */
    protected string $entityClass;

    /**
     * @inheritDoc
     */
    public function __construct($resource, string $entityClass)
    {
        $this->resource    = $resource;
        $this->entityClass = $entityClass;
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function execute(\Closure $closure): ResponseInterface
    {
        try {
            $result = call_user_func_array($closure, [$this->resource]);
            return new DbResponse($result, $this->entityClass);
        } catch (Throwable $exception) {
            throw $this->withError($exception);
        }
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function paginate(...$args): ResponseInterface
    {
        return $this->execute(function (Model $resource) use ($args) {
            $queryBuilder = $resource->newQuery();
            [$query]      = $args;

            $this->applyQuery($queryBuilder, $query);

            $columns    = $query['attributes']  ?? ['*'];
            $paging     = $query['page']  ?? [];
            $pageNumber = $paging['number'] ?? 1;
            $pageSize   = $paging['size']   ?? 25;

            return $queryBuilder->paginate($pageSize, $columns, 'page', $pageNumber);
        });
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function findAll(...$args): ResponseInterface
    {
        return $this->execute(function (Model $resource) use ($args) {
            $queryBuilder = $resource->newQuery();
            [$query]      = $args;

            $this->applyQuery($queryBuilder, $query);
            return $queryBuilder->get();
        });
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function find(...$args): ResponseInterface
    {
        return $this->execute(function (Model $resource) use ($args) {
            $id = $args[0] ?? null;
            if (!$id) {
                throw new ServerException("Couldn't find the given entity. The id is missing");
            }

            return $resource->newQuery()->find($id);
        });
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function store(...$args): ResponseInterface
    {
        return $this->execute(function (Model $resource) use ($args) {
            $attributes = $args[0] ?? [];

            /** @var Model $item */
            $item    = $resource->newQuery()->create($attributes);
            $message = "Criação de registro da entidade {$item->getTable()} com id {$item->getKey()}";

            Log::info($message, [
                "action"   => 'create',
                'register' => $item->toArray(),
                "table"    => $item->getTable(),
                "keyname"  => $item->getKeyName(),
                "key"      => $item->getKey()
            ]);

            return $item->getKey();
        });
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function update(...$args): ResponseInterface
    {
        return $this->execute(function (Model $resource) use ($args) {
            [$id, $attributes] = $args;
            if (!$id) {
                throw new ServerException("Couldn't update the given entity. The id is missing");
            }

            /** @var Model $model */
            $model = $resource->newQuery()->findOrFail($id);
            return with($model, static function (Model $item) use ($attributes) {
                $item->fill($attributes);
                $changes = $item->getDirty();
                $updated = $item->update();

                if ($updated) {
                    $message = "Atualização de registro da entidade {$item->getTable()} com id {$item->getKey()}";
                    Log::info($message, [
                        "action"  => 'update',
                        "table"   => $item->getTable(),
                        "keyname" => $item->getKeyName(),
                        "key"     => $item->getKey(),
                        'changes' => $changes
                    ]);
                }

                return $item->refresh();
            });
        });
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function delete(...$args): ResponseInterface
    {
        return $this->execute(function (Model $resource) use ($args) {
            $id = $args[0] ?? null;
            if (!$id) {
                throw new ServerException("Couldn't delete the given entity. The id is missing");
            }

            /** @var Model $item  */
            $item    = $resource->newQuery()->findOrFail($id);
            $deleted = $item->delete();

            if ($deleted) {
                Log::info("Exclusão da entidade {$item->getTable()} com id {$item->getKey()}", [
                    "action"  => 'delete',
                    "table"   => $item->getTable(),
                    "keyname" => $item->getKeyName(),
                    "key"     => $item->getKey(),
                ]);
            }

            return $deleted;
        });
    }

    /**
     * @inheritDoc
     */
    public function withError(Throwable $error): Throwable
    {
        if ($error instanceof QueryException) {
            return new ServerException($error->getMessage());
        }

        return new ClientException($error->getMessage());
    }
}
