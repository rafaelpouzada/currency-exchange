<?php

namespace Shared\Support;

use ArrayIterator;
use ArrayAccess;
use IteratorAggregate;
use Traversable;

abstract class FillableAttributeSet implements ArrayAccess, IteratorAggregate
{
    /**
     * @var array
     */
    protected array $attributes;

    /**
     * FillableAttributeSet's constructor
     */
    public function __construct()
    {
        $this->attributes = $this->makeFillableAttributes();
    }

    /**
     * Retorna a lista dos atributos que podem ser preenchidos.
     *
     * @return array
     */
    abstract protected function makeFillableAttributes(): array;

    /**
     * Get an iterator for the items.
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->attributes);
    }

    /**
     * Atributos que podem ser preenchidos na criação de endereços.
     *
     * @return array
     */
    public function getFillableAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @inheritdoc
     */
    public function offsetExists(mixed $offset): bool
    {
        return isset($this->attributes[$offset]);
    }

    /**
     * @inheritdoc
     */
    public function offsetGet(mixed $offset): mixed
    {
        return $this->attributes[$offset] ?? null;
    }

    /**
     * @inheritdoc
     */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->attributes[$offset] = $value;
    }

    /**
     * @inheritdoc
     */
    public function offsetUnset(mixed $offset): void
    {
        unset($this->attributes[$offset]);
    }

    /**
     * Filtra os atributos fornecidos que podem ser preenchidos.
     *
     * @param mixed  $attributes
     * @return mixed
     */
    public function filter(mixed $attributes): mixed
    {
        $values = [];
        if (!is_array($attributes)) {
            return $attributes;
        }

        foreach ($this as $key => $value) {
            $isList                    = is_string($key) && preg_match('/\.\*$/', $key);
            $isValueInstanceOfFillable = $value instanceof self;

            $field = value(static function () use ($isList, $isValueInstanceOfFillable, $key, $value) {
                if ($isList) {
                    // Remove os caracteres que identificam a chave do tipo lista
                    // De 'example.*' para 'example'
                    return str_replace('.*', '', $key);
                }

                if ($isValueInstanceOfFillable) {
                    return $key;
                }

                return $value;
            });

            // Ignora atributos que não foram fornecidos
            if (!array_key_exists($field, $attributes) || ($isList && !is_array($attributes[$field]))) {
                continue;
            }

            if (!$isValueInstanceOfFillable) {
                $values[$field] = $attributes[$field];
                continue;
            }

            if (!$isList) {
                $values[$field] = $value->filter($attributes[$field]);
                continue;
            }

            // percorre os itens da lista
            $values[$field] = [];
            foreach ($attributes[$field] as $item) {
                $values[$field][] = $value->filter($item);
            }
        }

        return $values;
    }
}
