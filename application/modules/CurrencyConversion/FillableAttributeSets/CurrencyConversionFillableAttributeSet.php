<?php

namespace CurrencyConversion\FillableAttributeSets;

use Shared\Support\FillableAttributeSet;

class CurrencyConversionFillableAttributeSet extends FillableAttributeSet
{
    /**
     * @return array
     */
    protected function makeFillableAttributes(): array
    {
        return [
            'from_currency',
            'to_currency',
            'amount',
        ];
    }
}
