<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register',[AuthController::class,'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::middleware('auth:api')->group(function () {
    Route::get('user', function (Request $request) {
        return $request->user();
    });
});
Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::get('/transactions/game/{status}', [TransactionController::class, 'getAllTransactionsGameTotal']);
Route::get('/transactions/game/{status}/{game_target}', [TransactionController::class, 'getUserTransactionsByGame']);
Route::patch('/transactions/detail/{id}', [TransactionController::class, 'updateTransactionStatus']);
Route::get('/transactions/detail/{transaction_id}', [TransactionController::class, 'getTransactionDetail']);

Route::get('/products/game/{game_id}', [ProductController::class, 'getProductsByGame']);
Route::get('/products/game', [ProductController::class, 'getGames']);

Route::post('/file/image', [FileController::class, 'uploadImage']);
Route::delete('/file/image/{filename}', [FileController::class, 'deleteImage']);

Route::post('/transactions/game', [TransactionController::class, 'createUserTransaction']);

