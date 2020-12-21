<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * @param User $user
     * @return bool|null
     */
    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }

        return null;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function index(User $user): bool
    {
        return false;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return false;
    }
}
