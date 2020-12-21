<?php


namespace App\Repositories;


use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserRepository
{
    public function findById($id)
    {
        return User::query()->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findAll()
    {
        return User::query();
    }

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getForTable($request)
    {
        $search = $request['search'] ?? null;

        if ($request['sort']) {
            $sortBy = trim($request['sort'],'-+') ?? 'name';
            $method = strpos($request['sort'],'-') === 0 ? 'desc' : 'asc';
        } else {
            $sortBy = 'name';
            $method = 'asc';
        }

        $users = User::query();

        if ($search) {
            $users->where('name', 'LIKE', '%'.$search.'%');
            $users->orWhere('email', 'LIKE', '%'.$search.'%');
        }

        return $users
            ->orderBy($sortBy, $method)
            ;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getForSelect()
    {
        return User::query()->select('id', 'name');
    }
}
