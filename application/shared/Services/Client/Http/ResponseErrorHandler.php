<?php

namespace Shared\Services\Client\Http;

use Shared\Services\Client\Contracts\Http\IResponseErrorHandler;
use Shared\Services\Client\Exception\{
    ClientException,
    RequestException,
    ServerException,
    UnauthorizedException,
    ValidationException
};
use Psr\Http\Message\ResponseInterface;

class ResponseErrorHandler implements IResponseErrorHandler
{
    /**
     * Http client response.
     *
     * @var ResponseInterface $response
     */
    protected ResponseInterface $response;

    /**
     * @inheritDoc
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    /**
     * @inheritDoc
     * @throws ClientException
     * @throws ServerException
     * @throws RequestException
     */
    public function handle(): mixed
    {
        $statusCode = $this->response->getStatusCode();

        $method = "onStatus{$statusCode}";
        if (method_exists($this, $method)) {
            return $this->$method();
        }

        $clientError = self::ERRORS[$statusCode] ?? null;
        if ($clientError) {
            throw new ClientException($clientError, $statusCode);
        }

        $serverError = self::FAILURES[$statusCode] ?? null;
        if ($serverError) {
            throw new ServerException($serverError, $statusCode);
        }

        throw new RequestException('Unknown Error', $statusCode);
    }

    /**
     * Tratamento para requisições não autorizadas.
     *
     * @return void
     * @throws UnauthorizedException
     */
    protected function onStatus401(): void
    {
        $contents       = $this->response->getBody()->getContents();
        $contents       = json_decode($contents, true);
        $defaultMessage = 'Unauthorized';

        throw new UnauthorizedException($contents['error'] ?? $defaultMessage, 401);
    }

    /**
     * Tratamento para erros de validação.
     *
     * @return void
     * @throws ValidationException
     */
    protected function onStatus422(): void
    {
        $contents = $this->response->getBody()->getContents();
        $contents = json_decode($contents, true);

        throw new ValidationException($contents['errors'] ?? []);
    }

    /**
     * Tratamento de erros de requisição.
     *
     * @return void
     * @throws RequestException
     */
    protected function onStatus402(): void
    {
        $contents       = $this->response->getBody()->getContents();
        $contents       = json_decode($contents, true);
        $defaultMessage = 'An error occurred when trying to communicate with the prohemo API';

        throw new RequestException($contents['message'] ?? $defaultMessage, 422);
    }

    /**
     * Tratamento para erros de validação.
     *
     * @return void
     * @throws ValidationException
     */
    protected function onStatus400(): void
    {
        $this->onStatus422();
    }
}
