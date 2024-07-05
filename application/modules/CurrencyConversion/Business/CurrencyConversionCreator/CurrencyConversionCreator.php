<?php

namespace CurrencyConversion\Business\CurrencyConversionCreator;

use ArrayAccess;
use CurrencyConversion\Entities\Transaction;
use CurrencyConversion\Enums\Currency;
use CurrencyConversion\FillableAttributeSets\CurrencyConversionFillableAttributeSet;
use CurrencyConversion\Repositories\Contracts\ICurrencyConversionRepository;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Enum;
use Shared\Exceptions\NotFoundException;
use Shared\Services\Entities\CurrencyConversion;
use Shared\Services\Repositories\Facades\ExchangeRatesRepository;

class CurrencyConversionCreator implements Contracts\ICurrencyConversionCreator
{
    /**
     * CurrencyConversionCreator constructor.
     *
     * @param ICurrencyConversionRepository $currencyConversionRepository
     */
    public function __construct(protected ICurrencyConversionRepository $currencyConversionRepository)
    {
    }

    /**
     * @param array|ArrayAccess $attributes
     *
     * @return Transaction
     * @throws NotFoundException
     */
    public function store(array|ArrayAccess $attributes): Transaction
    {
        if ($attributes instanceof Arrayable) {
            $attributes = $attributes->toArray();
        }

        $attributes = $this->filterAttributes($attributes);
        $this->validateAttributes($attributes);

        /** @var CurrencyConversion $currencyConversion */
        $currencyConversion = ExchangeRatesRepository::getExchangeRate(
            $attributes['from_currency'],
            $attributes['to_currency']
        );

        if (!$currencyConversion) {
            throw new NotFoundException('Currency conversion not found');
        }

        $attributes = [
            'user_id'           => Auth::guard('api')->id(),
            'from_amount'       => $attributes['amount'],
            'amount'            => $attributes['amount'] * $currencyConversion->conversion_rate,
            'from_currency'     => $currencyConversion->base_code,
            'to_currency'       => $currencyConversion->target_code,
            'conversion_rate'   => $currencyConversion->conversion_rate,
        ];

        /** @var Transaction $transaction */
        $transaction = $this->currencyConversionRepository->store($attributes);
        return $transaction;
    }

    /**
     * Return the filtered attributes.
     *
     * @param array $attributes
     * @return array
     */
    protected function filterAttributes(array $attributes): array
    {
        return (new CurrencyConversionFillableAttributeSet())->filter($attributes);
    }

    /**
     * Validate the attributes.
     *
     * @param array $attributes
     * @return void
     */
    protected function validateAttributes(array $attributes): void
    {
        Validator::make($attributes, [
            'amount'        => ['required', 'numeric'],
            'from_currency' => ['required', new Enum(Currency::class)],
            'to_currency'   => ['filled', new Enum(Currency::class)],
        ])->validate();
    }
}
