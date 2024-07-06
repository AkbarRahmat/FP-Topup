<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;

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

Route::post('register',[AuthController::class,'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('forgot-password', [ForgotPasswordController::class, 'sendOtp']);
Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword']);

Route::post('/users/generate', [UserController::class, 'generate']);
Route::get('/products/game', [ProductController::class, 'getGames']);
Route::get('/products/game/{game_id}', [ProductController::class, 'getProductsByGame']);


// Admin and Seller
Route::middleware('role-admin-seller')->group(function() {
    Route::get('/transactions/game/{status}', [TransactionController::class, 'getAllTransactionsGameTotal']);
    Route::patch('/transactions/detail/{id}', [TransactionController::class, 'updateTransactionStatus']);

    Route::post('/file/image', [FileController::class, 'uploadImage']);
});


// Admin
Route::middleware('role-admin')->group(function() {
    Route::put('/product/game/{product_id}', [ProductController::class, 'updatePrice']);
    Route::delete('/file/image/{filename}', [FileController::class, 'deleteImage']);
});


// Buyer
Route::middleware('role-buyer')->group(function() {
    Route::post('/transactions/game', [TransactionController::class, 'createUserTransaction']);
});


// Everyone
Route::middleware('role-everyone')->group(function() {
    Route::get('/transactions/game/{status}/{game_target}', [TransactionController::class, 'getUserTransactionsByGame']);
    Route::get('/transactions/detail/{transaction_id}', [TransactionController::class, 'getTransactionDetail']);
});
