<?php

namespace Shared\Exceptions;

use Exception;
use Throwable;

class ServerException extends Exception implements Contracts\IServerException
{
    /**
     * ClientExpetion's constructor
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = 'Ops! something went wrong',
        int $code = 500,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
