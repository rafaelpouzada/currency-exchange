<?php

namespace Shared\Services\Client\Exception;

use Throwable;

class ValidationException extends ClientException
{
    /**
     * Armazena os erros de validação.
     *
     * @var array $errors
    */
    protected array $errors = [];

    /**
     * ValidationException constructor.
     *
     * @param array $errors
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(array $errors = [], int $code = 422, Throwable $previous = null)
    {
        parent::__construct('The given data was invalid.', $code, $previous);
        $this->errors = $errors;
    }

    /**
     * Retorna os erros de validação.
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
