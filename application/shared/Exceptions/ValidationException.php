<?php

namespace Shared\Exceptions;

use Illuminate\Http\JsonResponse;
use Exception;
use Throwable;

class ValidationException extends Exception implements Contracts\IClientException
{
    /**
     * Erros lançados pela API Commerce.
     *
     * @var array $errors
    */
    protected array $errors = [];

    /**
     * ApiCommerceValidationException's constructor
     *
     * @param array          $errors
     * @param int            $code
     * @param Throwable|null $previous
     */
    public function __construct($errors = [], int $code = 422, Throwable $previous = null)
    {
        parent::__construct('The given data was invalid.', $code, $previous);

        $this->errors = $errors;
    }

    /**
     * Retorna os erros lançados pela API Commerce.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        $body = [
            "message" => $this->getMessage(),
            "errors"  => $this->getErrors(),
        ];

        return new JsonResponse($body, $this->getCode());
    }
}
