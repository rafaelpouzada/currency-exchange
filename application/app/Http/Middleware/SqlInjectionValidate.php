<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Shared\Exceptions\ClientException;

class SqlInjectionValidate
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     * @throws ClientException
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $patterns = [
            // SQL Injection patterns
            '/\bunion\b/i',
            '/\bselect\b/i',
            '/\bfrom\b/i',
            '/\bwhere\b/i',
            '/\bdrop\b/i',
            '/\binsert\b/i',
            '/\bupdate\b/i',
            '/\bdelete\b/i',
            '/\b--\b/',
            '/\binto\b/i',
            '/\bload_file\b/i',
            '/\boutfile\b/i',
            '/\bjoin\b/i',
            '/\bdumpfile\b/i',
            '/\bhaving\b/i',
            '/\bgroup by\b/i',
            '/\bconcat\b/i',
            '/\btruncate\b/i',
            '/\bdeclare\b/i',
            '/\bexec\b/i',
            '/\bcast\b/i',
            '/\bconvert\b/i',
            '/\bextractvalue\b/i',
            '/\bupdatexml\b/i',
            '/\bascii\b/i',
            '/["\']\s*\bor\b\s*["\']/i',
        ];

        $input = $request->all();

        $this->checkInput($input, $patterns);

        return $next($request);
    }

    /**
     * Recursively check input for SQL Injection patterns.
     *
     * @param mixed $input
     * @param array $patterns
     * @throws ClientException
     */
    protected function checkInput(mixed $input, array $patterns): void
    {
        if (is_array($input)) {
            foreach ($input as $param) {
                $this->checkInput($param, $patterns);
            }
        } elseif (is_string($input)) {
            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $input)) {
                    // SQL Injection attempt detected, return an error response
                    throw new ClientException('Invalid request.', 400);
                }
            }
        }
    }
}
