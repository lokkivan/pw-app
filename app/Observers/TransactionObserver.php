<?php

namespace App\Observers;

use App\Helpers\AccountHelper;
use App\Models\Transaction;

/**
 * Class TransactionObserver
 * @package App\Observers
 */
class TransactionObserver
{
    public function created(Transaction $transaction)
    {
        AccountHelper::writeOffFunds($transaction->sender->account, $transaction->amount);
        AccountHelper::creditFunds($transaction->recipient->account, $transaction->amount);
    }
}
