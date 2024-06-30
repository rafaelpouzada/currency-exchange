<?php

namespace Shared\Entities\Concerns;

use Shared\Entities\Support\Contracts\IArrayable;
use Shared\Entities\Support\Str;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 * @SuppressWarnings(PHPMD.UndefinedVariable)
 */
trait HasMutations
{
    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    protected static bool $snakeAttributes = true;

    /**
     * The cache of the mutated attributes for each class.
     *
     * @var array
     */
    protected static array $mutatorCache = [];

    /**
     * Determine if a set mutator exists for an attribute.
     *
     * @param string $key
     * @return bool
     */
    protected function hasSetMutator(string $key): bool
    {
        return method_exists($this, 'set' . Str::studly($key) . 'Attribute');
    }

    /**
     * Set the value of an attribute using its mutator.
     *
     * @param string $key
     * @param  mixed $value
     * @return mixed
     */
    protected function setMutatedAttributeValue(string $key, mixed $value): mixed
    {
        return $this->{'set' . Str::studly($key) . 'Attribute'}($value);
    }

    /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param string $key
     * @return bool
     */
    protected function hasGetMutator(string $key): bool
    {
        return method_exists($this, 'get' . Str::studly($key) . 'Attribute');
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param string $key
     * @param  mixed $value
     * @return mixed
     */
    protected function mutateAttribute(string $key, mixed $value): mixed
    {
        return $this->{'get' . Str::studly($key) . 'Attribute'}($value);
    }

    /**
     * Get the value of an attribute using its mutator for array conversion.
     *
     * @param string $key
     * @param  mixed $value
     * @return mixed
     */
    protected function mutateAttributeForArray(string $key, mixed $value): mixed
    {
        $value = $this->mutateAttribute($key, $value);

        return $value instanceof IArrayable ? $value->toArray() : $value;
    }

    /**
     * Get the mutated attributes for a given instance.
     *
     * @return array
     */
    public function getMutatedAttributes(): array
    {
        $class = static::class;

        if (! isset(static::$mutatorCache[$class])) {
            static::cacheMutatedAttributes($class);
        }

        return static::$mutatorCache[$class];
    }

    /**
     * Extract and cache all the mutated attributes of a class.
     *
     * @param string $class
     * @return void
     */
    public static function cacheMutatedAttributes(string $class): void
    {
        static::$mutatorCache[$class] = array_map(
            static function ($match) {
                return lcfirst(static::$snakeAttributes ? Str::snake($match) : $match);
            },
            static::getMutatorMethods($class)
        );
    }

    /**
     * Get all the attribute mutator methods.
     *
     * @param  mixed $class
     * @return array
     */
    protected static function getMutatorMethods(mixed $class): array
    {
        preg_match_all('/(?<=^|;)get([^;]+?)Attribute(;|$)/', implode(';', get_class_methods($class)), $matches);

        return $matches[1];
    }
}
