<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Router;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Shared\Exceptions\ClientException;
use Shared\Exceptions\Contracts\IClientException;
use Shared\Exceptions\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * @inheritDoc
     */
    public function render($request, Throwable $e): Response
    {
        if ($e instanceof LaravelValidationException) {
            return parent::render($request, new ValidationException($e->errors()));
        }

        if ($e instanceof AuthorizationException) {
            return parent::render($request, $e);
        }

        if ($e instanceof HttpExceptionInterface) {
            return parent::render($request, $e);
        }

        $code = $e->getCode();
        if ($code >= 400 && $code < 500 && !($e instanceof IClientException)) {
            $e = new ClientException($e->getMessage(), $code, $e);
        }

        if ($e instanceof IClientException) {
            $body = ['message' => $e->getMessage()];
            return Router::toResponse($request, new JsonResponse($body, $e->getCode()));
        }

        return parent::render($request, $e);
    }
}
