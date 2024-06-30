<?php

namespace Shared\Exceptions;

use Exception;
use Throwable;

class ClientException extends Exception implements Contracts\IClientException
{
    /**
     * ClientException's constructor
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $message, int $code = 400, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
