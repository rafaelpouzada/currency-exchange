<?php

namespace CurrencyConversion\Business\CurrencyConversionFinder;

use CurrencyConversion\Entities\Transaction;
use CurrencyConversion\Repositories\Contracts\ICurrencyConversionRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Shared\Exceptions\NotFoundException;

class CurrencyConversionFinder implements Contracts\ICurrencyConversionFinder
{
    /**
     * CurrencyConversionFinder constructor.
     *
     * @param ICurrencyConversionRepository $repository
     */
    public function __construct(
        protected ICurrencyConversionRepository $repository
    ) {
    }

    /**
     * @inheritDoc
     * @throws NotFoundException
     */
    public function find(int $id): Transaction
    {
        /** @var Transaction $transaction */
        $transaction = $this->repository->find($id);

        if (!$transaction) {
            throw new NotFoundException('Transaction not found');
        }

        return $transaction;
    }

    /**
     * @inheritDoc
     */
    public function paginate(array $query = []): LengthAwarePaginator
    {
        return $this->repository->paginate($query);
    }
}
