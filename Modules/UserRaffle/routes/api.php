<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\app\Http\Controllers\SubscriptionController;
use Modules\Admin\app\Http\Controllers\UserController;
use Modules\Client\app\Http\Controllers\ReceiptController;
use Modules\UserRaffle\app\Http\Controllers\BankAccountsController;
use Modules\UserRaffle\app\Http\Controllers\RaffleController;

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

Route::middleware(['auth:sanctum'])->prefix('v1')->name('api.')->group(function () {
    Route::get('userraffle', fn (Request $request) => $request->user())->name('userraffle');
});


Route::prefix('raffles')->middleware(['auth:sanctum'])->group(function () {
   Route::patch('subscriptions/plans/{id}',[SubscriptionController::class,'updateSubUser']);
   Route::post('subscriptions/plans/voucher',[UserController::class,'createFilePaymentPlan']);
   Route::resource('lottery',RaffleController::class);
   Route::post('custom/update/{id}',[RaffleController::class,'updateRaffle']);
   Route::get('list/raffles/{taxid}',[RaffleController::class,'listForItems']);
   Route::get('list/confirm/payment/{taxid}',[ReceiptController::class ,'showByUser']);
   Route::get('receipt/{id}',[ReceiptController::class,'show']);
   Route::patch('receipt/{id}',[ReceiptController::class,'update']);
   Route::get('bank-accounts/user/{user_id}',[BankAccountsController::class,'showAccountsByUser']);
   Route::resource('me/bank-accounts',BankAccountsController::class);
});



