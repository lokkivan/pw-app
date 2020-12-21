<?php


namespace App\Repositories;

use App\Helpers\RequestHelper;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;


class TransactionsRepository
{
    public function findById($id)
    {
        return Transaction::query()->find($id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function findAll()
    {
        return Transaction::query();
    }

    public function findLastForUser(User $user, $limit)
    {
        return Transaction::query()
            ->where('sender_id', $user->id)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->with('sender', 'recipient')
            ->get()
        ;
    }

    /**
     * @param $request
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getForTable($request)
    {
        $query = Transaction::query()->with('sender', 'recipient');

        if ($request->user()->isGuest()) {
            $query->where('sender_id', $request->user()->id);
            $query->orWhere('recipient_id', $request->user()->id);
        }

        if ($request->has('search')) {
            $query->whereHas('sender', function($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request['search'].'%');
            });
            $query->orWhereHas('recipient', function($q) use ($request) {
                $q->where('name', 'LIKE', '%'.$request['search'].'%');
            });
        }

        if ($request->has('dateRange') && count($request['dateRange']) === 2) {
            $query->whereDate('created_at', '>=' , Carbon::parse($request['dateRange'][0]));
            $query->whereDate('created_at', '<=' , Carbon::parse($request['dateRange'][1]));
        }

        $orderBy = RequestHelper::sortFormat($request->get('sort'));

        return $query->orderBy($orderBy['sort'], $orderBy['method']);
    }
}
