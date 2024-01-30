<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Client\app\Http\Controllers\PaymentTicketController;

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

Route::middleware(['auth:sanctum'])->prefix('client')->name('api.')->group(function () {
    Route::get('client', fn (Request $request) => $request->user())->name('client');
    Route::get('user', fn (Request $request) => response()->json(['test' => 1]));
});

Route::resource('payment/raffle',PaymentTicketController::class);