<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Modules\Admin\app\Http\Controllers\SubscriptionController;
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
    Route::post('logout', [AuthController::class,'logout']);
});

Route::get('public/raffles', [RaffleController::class,'indexRaffles']);