<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string|null $version = null;

    /**
     * Define a versão da api.
     *
     * @return $this
     */
    protected function setApiVersion(): self
    {
        $this->version = env('API_VERSION', 'v1');

        return $this;
    }

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->routes(function () {
            $this->setApiVersion()
                ->mapApiRoutes()
                ->registerUserRoutes()
                ->registerCurrencyConversionRoutes();
        });
    }

    /**
     * @return string
     */
    protected function getApiPrefix(): string
    {
        return collect([
            env('API_PREFIX', 'api'),
            $this->version,
        ])
            ->filter()
            ->join('/');
    }

    /**
     * Define the "api" routes for the application.
     *
     * @return $this
     */
    protected function mapApiRoutes(): static
    {
        Route::prefix($this->getApiPrefix())
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));

        return $this;
    }

    /**
     * Register the user routes for the User module.
     *
     * @return $this
     */
    public function registerUserRoutes(): static
    {
        Route::prefix($this->getApiPrefix())
            ->namespace('\\Auth\\Http\\Controllers')
            ->middleware('api')
            ->group(base_path('modules/Auth/routes/api.php'));

        return $this;
    }

    /**
     * Register the currency conversion routes for the CurrencyConversion module.
     *
     * @return $this
     */
    public function registerCurrencyConversionRoutes(): static
    {
        Route::prefix($this->getApiPrefix())
            ->namespace('\\CurrencyConversion\\Http\\Controllers')
            ->middleware('api')
            ->group(base_path('modules/CurrencyConversion/routes/api.php'));

        return $this;
    }
}
