<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PaymentController;

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
/*
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
Route::post('login', [UserController::class, 'login']);
Route::post('register', [UserController::class, 'register']);
Route::post('logout', [UserController::class, 'logout'])->middleware('auth:sanctum');

Route::group(['prefix' => 'transactions', 'middleware' => 'auth:sanctum'], function () {
    Route::get('/{user_id}', [TransactionController::class, 'showAll']);
    Route::get('show/{id}', [TransactionController::class, 'show']);
    Route::get('store/{data}', [TransactionController::class, 'store']);
});

Route::group(['prefix' => 'purchase', 'middleware' => 'auth:sanctum'], function () {
    Route::post('details', [PaymentController::class, 'purchaseDetails']);
    Route::any('confirm/{id}/{user_id}', [PaymentController::class, 'processPayment']);
    Route::post('refund', [PaymentController::class, 'processRefund']);
});

