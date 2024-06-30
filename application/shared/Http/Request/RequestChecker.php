<?php

namespace Shared\Http\Request;

use Closure;
use Illuminate\Http\Request;
use Shared\Exceptions\ClientException;

class RequestChecker
{
    /**
     * Verifica o cabeçalho da requisição.
     * Negociação de conteúdo entre cliente e servidor.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     * @throws ClientException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!empty($request->getContent()) && !$request->isJson()) {
            throw new ClientException('415 Unsupported Media Type', 415);
        }

        if (!$request->expectsJson()) {
            throw new ClientException('406 Not Acceptable', 406);
        }

        return $next($request);
    }
}
