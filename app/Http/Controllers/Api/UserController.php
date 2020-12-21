<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UsersRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class UserController
 * @package App\Http\Controllers\Api\Settings
 */
class UserController extends Controller
{
    const PER_PAGE = 10;

    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * UserController constructor.
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param UsersRequest $request
     * @return JsonResponse
     */
    public function index(UsersRequest $request): JsonResponse
    {
        try {
            $users = $this->userRepository
                ->getForTable($request->all())
                ->paginate(self::PER_PAGE)
            ;

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'users' => $users,
            ]
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function block(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(),[
            'id' => 'required|numeric',
        ]);

        if(!$validator->fails()) {
            try {
                $validData = $validator->validate();
                $user = $this->userRepository->findById($validData['id']);

                if (!$user) {
                    return response()->json([
                        'status' => false,
                        'message' => __('user.user_not_found')
                    ]);
                }

                $user->is_blocked = !$user->is_blocked;
                $user->save();

                return response()->json([
                    'status' => true,
                    'message' => str_replace(
                        ':email',
                        $user->email,
                        $user->is_blocked ? __('user.blocked') : __('user.unblocked')
                    )
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'status' => false,
                    'message' => $e->getMessage()
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => $validator->errors()
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function current(Request $request): JsonResponse
    {
        $user = $request->user();
        $user['account'] = $user->account;
        return response()->json($user);
    }

    /**
     * @return JsonResponse
     */
    public function all(): JsonResponse
    {
        try {
            $users = $this->userRepository
                ->getForSelect()
                ->get()
            ;

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'users' => $users,
            ]
        ]);
    }
}
