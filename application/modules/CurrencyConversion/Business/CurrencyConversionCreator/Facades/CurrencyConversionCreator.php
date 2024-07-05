<?php

namespace CurrencyConversion\Business\CurrencyConversionCreator\Facades;

use CurrencyConversion\Business\CurrencyConversionCreator\Contracts\ICurrencyConversionCreator;
use CurrencyConversion\Entities\Transaction;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Transaction store(array $attributes)
 */
class CurrencyConversionCreator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return ICurrencyConversionCreator::class;
    }
}
