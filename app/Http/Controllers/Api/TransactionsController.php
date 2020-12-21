<?php

namespace App\Http\Controllers\Api;

use App\Helpers\AccountHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Requests\TransactionsRequest;
use App\Models\Transaction;
use App\Repositories\TransactionsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class TransactionsController
 * @package App\Http\Controllers\Api
 */
class TransactionsController extends Controller
{
    const PER_PAGE = 10;

    /**
     * @var TransactionsRepository
     */
    private $transactionsRepository;

    /**
     * TransactionsController constructor.
     * @param TransactionsRepository $transactionsRepository
     */
    public function __construct(TransactionsRepository $transactionsRepository)
    {
        $this->transactionsRepository = $transactionsRepository;
    }

    /**
     * @param TransactionsRequest $request
     * @return JsonResponse
     */
    public function index(TransactionsRequest $request): JsonResponse
    {
        try {
            $transactions = $this->transactionsRepository
                ->getForTable($request)
                ->paginate(self::PER_PAGE);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'transactions' => $transactions,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function last(Request $request): JsonResponse
    {
        try {
            $transactions = $this->transactionsRepository->findLastForUser($request->user(), self::PER_PAGE);

            return response()->json([
                'status' => true,
                'data' => [
                    'transactions' => $transactions,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * @param CreateTransactionRequest $request
     * @return JsonResponse
     */
    public function create(CreateTransactionRequest $request): JsonResponse
    {
        \DB::beginTransaction();

        try {
            if(AccountHelper::checkIsEnoughAmount($request->user(), $request->get('amount'))) {
                throw new \Exception( __('transaction.not_enough_funds'));
            }

            $transaction = Transaction::factory()->make();
            $transaction->fill($request->all());
            $transaction->save();

            \DB::commit();
        } catch (\Exception $e) {

            \DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => __('transaction.created')
        ]);
    }
}
