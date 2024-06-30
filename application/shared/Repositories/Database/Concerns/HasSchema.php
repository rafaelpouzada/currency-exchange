<?php

namespace Shared\Repositories\Database\Concerns;

use Illuminate\Contracts\Support\Arrayable;

/**
 * @property array $schema
 * @property array $attributes
 *
 * @mixin Arrayable
 */
trait HasSchema
{
    /**
     * Return the schema of the model.
     *
     * @return array
     */
    public function getSchema(): array
    {
        if (property_exists($this, 'schema')) {
            return $this->schema;
        }

        return [];
    }

    /**
     * Return the model attribute key.
     *
     * @param string $entityAttributeKey
     * @return string
     */
    public function getModelAttributeKey(string $entityAttributeKey): string
    {
        $modelKey = array_search($entityAttributeKey, $this->getSchema(), true);
        if (!is_string($modelKey)) {
            return $entityAttributeKey;
        }

        return $modelKey;
    }


    /**
     * Return the entity attribute key.
     *
     * @param string $modelAttributeKey
     * @return string
     */
    public function getEntityAttributeKey(string $modelAttributeKey): string
    {
        return $this->getSchema()[$modelAttributeKey] ?? $modelAttributeKey;
    }

    /**
     * Return the model attributes.
     *
     * @return array
     */
    public function toEntityAttributes(): array
    {
        if (!property_exists($this, 'schema')) {
            return $this->toArray();
        }

        $entityAttributes = [];
        foreach ($this->toArray() as $key => $value) {
            $attributeName = $this->getEntityAttributeKey($key);
            $entityAttributes[$attributeName] = $value;
        }

        return $entityAttributes;
    }
}
