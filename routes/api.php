<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\Api\TransactionsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    // USERS

     Route::group(['prefix' => '/users'], function () {
         Route::group(['middleware' => 'can:index,' . \App\Models\User::class], function () {
            Route::get('', [UserController::class, 'index']);
         });
         Route::group(['middleware' => 'can:update,' . \App\Models\User::class], function () {
             Route::put('block', [UserController::class, 'block']);
         });
         Route::get('all', [UserController::class, 'all']);
    });


    // TRANSACTIONS
    Route::group(['prefix' => '/transactions'], function () {
        Route::get('', [TransactionsController::class, 'index']);
        Route::get('last', [TransactionsController::class, 'last']);
        Route::post('create', [TransactionsController::class, 'create']);
    });

    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('user', [UserController::class, 'current']);
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
});
