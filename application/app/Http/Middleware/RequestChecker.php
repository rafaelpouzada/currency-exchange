<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shared\Exceptions\ClientException;

class RequestChecker
{
    /**
     * Check the request header.
     * Content negotiation between client and server.
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
