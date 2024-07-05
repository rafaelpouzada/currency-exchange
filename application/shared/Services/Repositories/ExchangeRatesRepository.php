<?php

namespace Shared\Services\Repositories;

use Illuminate\Support\Arr;
use Shared\Services\Entities\CurrencyConversion;
use Shared\Services\Repositories\Contracts\IExchangeRatesRepository;

class ExchangeRatesRepository extends IExchangeRatesRepository
{
    /**
     * @inheritDoc
     */
    protected function getEntityClass(): string
    {
        return CurrencyConversion::class;
    }

    /**
     * Autentica um usuário no sistema.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return CurrencyConversion|null
     */
    public function getExchangeRate(string $fromCurrency, string $toCurrency): ?CurrencyConversion
    {
        $response = $this->client()
            ->getRequest()
            ->get("/pair/$fromCurrency/$toCurrency");

        $result = $response->getContents();
        if (empty($result)) {
            return null;
        }

        if (Arr::get($result, 'result') !== 'success') {
            return null;
        }

        /** @var CurrencyConversion $currencyConversion */
        $currencyConversion = $this->makeResponseFormatter($response)
            ->toEntity();

        return $currencyConversion;
    }
}
