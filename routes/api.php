<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Modules\Admin\app\Http\Controllers\SubscriptionController;
use Modules\Admin\app\Http\Controllers\UserController;
use Modules\UserRaffle\app\Http\Controllers\PaymentController;
use Modules\UserRaffle\app\Http\Controllers\RaffleController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('login', [AuthController::class, 'login']);
Route::post('register',[AuthController::class,'register']);
Route::post('register/confirm/{id}',[AuthController::class,'confirRegister']);
Route::get('subscriptions',[SubscriptionController::class,'getActives']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('refresh/user',[AuthController::class,'refresh']);
    Route::patch('user/profile/{id}',[UserController::class,'update']);
    Route::post('user/avatar/{id}',[UserController::class,'storeAvatar']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::resource('card-transaction/plans',PaymentController::class);
});
Route::resource('payment/tickets/card-transaction',PaymentController::class);

Route::get('public/raffles', [RaffleController::class,'indexRaffles']);
Route::get('public/raffles/{id}', [RaffleController::class,'show']);
Route::get('public/raffles/tickets/{id}',[RaffleController::class,'showTicketsByRaffle']);
Route::post('public/recovery/password',[AuthController::class,'restorePasswords']);
