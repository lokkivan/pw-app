<?php

namespace App\Helpers;

use App\Models\Account;
use App\Models\User;

/**
 * Class RequestHelper
 * @package App\Helpers
 */
class RequestHelper
{
    /**
     * @param $sort
     * @return array|string[]
     */
    public static function sortFormat(string $sort): array
    {
        if ($sort) {
            return [
                'sort' => trim($sort,'-+') ?? 'id',
                'method' => strpos($sort,'-') === 0 ? 'desc' : 'asc',
            ];
       }

        return [
            'sort' => 'id',
            'method' =>  'desc',
        ];
    }
}
