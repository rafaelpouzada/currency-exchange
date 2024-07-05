<?php

namespace Shared\Services\Pagination;

use ArrayAccess;
use ArrayIterator;
use IteratorAggregate;
use JsonSerializable;
use Shared\Support\Contracts\ICollection;
use Traversable;

class Paginator implements Contracts\IPaginator, ArrayAccess, IteratorAggregate, JsonSerializable
{
    /**
     * @var ICollection $items
     */
    protected ICollection $items;

    /**
     * @var array $paging
     */
    protected array $paging;

    /**
     * @inheritDoc
     */
    public function __construct(ICollection $items, array $paging)
    {
        $this->items  = $items;
        $this->paging = $paging;
    }

    /**
     * @inheritDoc
     */
    public function getItems(): ICollection
    {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function getPageNumber(): int
    {
        return $this->paging['number'] ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function getPageSize(): int
    {
        return $this->paging['size'] ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function getTotal(): int
    {
        return $this->paging['total'] ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items->all());
    }

    /**
     * Determine if an item exists at an offset.
     *
     * @param  mixed $offset
     * @return bool
     */
    public function offsetExists(mixed $offset): bool
    {
        return $this->items->has($offset);
    }

    /**
     * Get an item at a given offset.
     *
     * @param  mixed $offset
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->items->get($offset);
    }

    /**
     * Set the item at a given offset.
     *
     * @param  mixed $offset
     * @param  mixed $value
     * @return void
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->items->put($offset, $value);
    }

    /**
     * Unset the item at a given offset.
     *
     * @param  mixed $offset
     * @return void
     */
    public function offsetUnset(mixed $offset): void
    {
        $this->items->forget($offset);
    }

    /**
     * @inheritDoc
     */
    public function count(): int
    {
        return $this->items->count();
    }

    /**
     * @inheritDoc
     */
    public function toArray(): array
    {
        return [
            'paging' => $this->paging,
            'items'  => $this->items->toArray(),
        ];
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
