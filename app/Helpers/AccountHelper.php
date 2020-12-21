<?php

namespace App\Helpers;

use App\Models\Account;
use App\Models\User;

/**
 * Class AccountHelper
 * @package App\Helpers
 */
class AccountHelper
{
    /**
     * @param User $user
     * @param $amount
     * @return bool
     */
    public static function checkIsEnoughAmount(User $user, $amount)
    {
        if($user->account->balance < $amount) {
            return true;
        }

        return false;
    }

    /**
     * @param Account $account
     * @param $amount
     */
    public static function writeOffFunds(Account $account, $amount)
    {
        $account->update([
            'balance' => $account->balance - $amount
        ]);
    }

    /**
     * @param Account $account
     * @param $amount
     */
    public static function creditFunds(Account $account, $amount)
    {
        $account->update([
            'balance' => $account->balance + $amount
        ]);
    }
}
