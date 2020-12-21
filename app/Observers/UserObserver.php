<?php

namespace App\Observers;

use App\Models\Account;
use App\Models\User;

/**
 * Class UserObserver
 * @package App\Observers
 */
class UserObserver
{
    public function created(User $user)
    {
        $user->account()->create([
            'balance' => Account::STARTING_BALANCE,
        ]);
    }
}
