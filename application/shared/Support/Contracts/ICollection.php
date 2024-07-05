<?php

namespace Shared\Support\Contracts;

use Countable;
use Illuminate\Contracts\Support\Arrayable;

interface ICollection extends Arrayable, Countable
{
    /**
     * Create a new collection.
     *
     * @param array $items
     */
    public function __construct(array $items = []);

    /**
     * Get all of the items in the collection.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Run a map over each of the items.
     *
     * @param  callable $callback
     * @return static
     */
    public function map(callable $callback): static;

    /**
     * Push one or more items onto the end of the collection.
     *
     * @param  mixed $values
     * @return static
     */
    public function push(...$values): static;

    /**
     * Put an item in the collection by key.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     * @return static
     */
    public function put(mixed $key, mixed $value): static;

    /**
     * Returns the item with the given key.
     *
     * @param  mixed $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get(mixed $key, mixed $default = null): mixed;

    /**
     * Remove an item from the collection by key.
     *
     * @param array|string $keys
     * @return $this
     */
    public function forget(array|string $keys): static;

    /**
     * Determines if the given offset exists.
     *
     * @param  mixed $key
     * @return bool
     */
    public function has(mixed $key): bool;
}
