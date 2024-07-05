<?php

namespace CurrencyConversion\Repositories;

use CurrencyConversion\Entities\Transaction;
use CurrencyConversion\Repositories\Models\TransactionModel;
use Shared\Repositories\Concerns\{
    CanFind,
    CanPaginate,
    CanStore
};
use Shared\Repositories\Database\DbRepository;

class CurrencyConversionRepository extends DbRepository implements Contracts\ICurrencyConversionRepository
{
    use CanFind;
    use CanPaginate;
    use CanStore;

    /**
     * @inheritDoc
     */
    public function getResourceClass(): string
    {
        return TransactionModel::class;
    }

    /**
     * @inheritDoc
     */
    public function getEntityClass(): string
    {
        return Transaction::class;
    }
}
