<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Shared\Exceptions\ClientException;

class Authenticate
{
    /**
     * Check if the user is authenticated.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ClientException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (!Auth::guard('api')->check()) {
            throw new ClientException('Unauthorized access.', 401);
        }

        return $next($request);
    }
}
