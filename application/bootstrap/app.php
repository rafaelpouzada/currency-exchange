<?php

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RequestChecker;
use App\Http\Middleware\SqlInjectionValidate;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\TrimStrings;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Routing\Middleware\SubstituteBindings;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->use([
            HandleCors::class,
            PreventRequestsDuringMaintenance::class,
            ValidatePostSize::class,
            TrimStrings::class,
            ConvertEmptyStringsToNull::class,
            SqlInjectionValidate::class
        ]);
        $middleware->appendToGroup('api', [
            SubstituteBindings::class,
        ]);
        $middleware->appendToGroup('api.restrict', [
            Authenticate::class,
            RequestChecker::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })->create();

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

return $app;
