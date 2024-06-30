<?php

namespace Shared\Repositories\Contracts;

use Closure;
use Shared\Entities\Entity;
use Throwable;

interface ResourceInterface
{
    /**
     * ResourceInterface's constructor.
     *
     * @param mixed                $resource
     * @param class-string<Entity> $entityClass
     */
    public function __construct(mixed $resource, string $entityClass);

    /**
     * Execute a closure with the base resource.
     * @example
     * $resource->execute(function($baseResource){
     *    // do something
     * })
     *
     * @param Closure $closure
     * @return ResponseInterface
     */
    public function execute(Closure $closure): ResponseInterface;

    /**
     * Return the paginated list of resources.
     *
     * @param mixed ...$args
     * @return ResponseInterface
     */
    public function paginate(...$args): ResponseInterface;

    /**
     * Return all resources.
     *
     * @param mixed ...$args
     * @return ResponseInterface
     */
    public function findAll(...$args): ResponseInterface;

    /**
     * Return the resource with the provided id.
     *
     * @param mixed ...$args
     * @return ResponseInterface
     */
    public function find(...$args): ResponseInterface;

    /**
     * Create a new resource.
     *
     * @param mixed ...$args
     * @return ResponseInterface
     */
    public function store(...$args): ResponseInterface;

    /**
     * Update the resource with the provided id.
     *
     * @param mixed ...$args
     * @return ResponseInterface
     */
    public function update(...$args): ResponseInterface;

    /**
     * Delete the resource with the provided id.
     *
     * @param mixed ...$args
     * @return ResponseInterface
     */
    public function delete(...$args): ResponseInterface;

    /**
     * Receive a new error.
     *
     * @param Throwable $error
     * @return Throwable
     */
    public function withError(Throwable $error): Throwable;
}
