<?php

namespace CurrencyConversion\Entities;

use Auth\Entities\User;
use CurrencyConversion\Enums\Currency;
use Shared\Entities\Entity;

/**
 * @property int                $user_id
 * @property float              $amount
 * @property value-of<Currency> $from_currency
 * @property value-of<Currency> $to_currency
 * @property float              $rate
 * @property User               $user
 */
 class Transaction extends Entity
{
    protected array $casts = [
        'amount' => 'float',
        'rate'   => 'float'
    ];

    /**
     * @param mixed $values
     * @return void
     */
    public function setUserAttribute(mixed $values): void
    {
        $this->attributes['user'] = new User($values);
    }
}
