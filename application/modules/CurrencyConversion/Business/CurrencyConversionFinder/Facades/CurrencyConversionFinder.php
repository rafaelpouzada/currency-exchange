<?php

namespace CurrencyConversion\Business\CurrencyConversionFinder\Facades;

use CurrencyConversion\Business\CurrencyConversionFinder\Contracts\ICurrencyConversionFinder;
use CurrencyConversion\Entities\Transaction;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Transaction           find(int $id)
 * @method static LengthAwarePaginator  paginate(array $filters = [])
 */
class CurrencyConversionFinder extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ICurrencyConversionFinder::class;
    }
}
