<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Seller\app\Http\Controllers\CommissionsController;
use Modules\Seller\app\Http\Controllers\RafflesController;

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
    Route::get('seller', fn (Request $request) => $request->user())->name('seller');
});

Route::prefix('seller')->middleware(['auth:sanctum'])->group(function () {
    Route::resource('raffles' ,RafflesController::class);
    Route::get('commission/by-user/{taxid}',[CommissionsController::class,'byUser']);
    Route::resource('me/commissions',CommissionsController::class);
 });
 