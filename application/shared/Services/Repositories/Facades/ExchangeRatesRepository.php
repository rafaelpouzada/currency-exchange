<?php

namespace Shared\Services\Repositories\Facades;

use Illuminate\Support\Facades\Facade;
use Shared\Entities\Entity;
use Shared\Services\Repositories\ExchangeRatesRepository as IExchangeRatesRepository;

/**
 * @method static Entity|null getExchangeRate(string $fromCurrency, string $toCurrency)
 */
class ExchangeRatesRepository extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return IExchangeRatesRepository::class;
    }
}
