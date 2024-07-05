<?php

namespace Shared\Services\Client\Http;

use Shared\Services\Client\Contracts\Http\IResponse;
use Psr\Http\Message\ResponseInterface;

class JsonResponse implements IResponse
{
    /**
     * The http response received.
     *
     * @var ResponseInterface $httpResponse
     */
    protected ResponseInterface $httpResponse;

    /**
     * The content of the response.
     *
     * @var array $contents
     */
    protected array $contents;

    /**
     * @inheritDoc
     */
    public function __construct(ResponseInterface $response)
    {
        $this->httpResponse = $response;
    }

    /**
     * @inheritDoc
     */
    public function getContents(): array
    {
        if (!isset($this->contents)) {
            $contents       = $this->httpResponse->getBody()->getContents();
            $this->contents = json_decode($contents, true);
        }

        return $this->contents;
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode(): int
    {
        return $this->httpResponse->getStatusCode();
    }
}
