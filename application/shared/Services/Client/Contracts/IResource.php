<?php

namespace Shared\Services\Client\Contracts;

use Shared\Services\Client\Contracts\Http\{IResponse, IResponseFormatter};
use Shared\Services\Client\Http\ResponseFormatter;
use Shared\Entities\Entity;

abstract class IResource
{
    /**
     * Service instance.
     *
     * @var IService
     */
    protected IService $service;

    /**
     * ProductResource's constructor.
     *
     * @param IService $service
     */
    public function __construct(IService $service)
    {
        $this->service = $service;
    }

    /**
     * Returns the service's base api uri.
     *
     * @return string
     */
    abstract protected function getApiUri(): string;

    /**
     * Returns the envelope used by the entity hydrator.
     *
     * @return class-string<Entity>
     */
    abstract protected function getEntityClass(): string;

    /**
     * Makes a new response formatter instance.
     *
     * @param IResponse $response
     * @return IResponseFormatter
     */
    protected function makeResponseFormatter(IResponse $response): IResponseFormatter
    {
        return new ResponseFormatter($response, $this->getEntityClass());
    }
}
