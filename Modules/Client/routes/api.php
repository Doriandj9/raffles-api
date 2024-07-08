<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Client\app\Http\Controllers\PaymentTicketController;
use Modules\Client\app\Http\Controllers\RatingController;
use Modules\Client\app\Http\Controllers\TicketController;

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

Route::middleware(['auth:sanctum'])->prefix('client')->group(function () {
    Route::get('me/tickets/{taxid}',[TicketController::class ,'showByUser']);
});

Route::resource('payment/raffle',PaymentTicketController::class);
Route::resource('rating',RatingController::class);