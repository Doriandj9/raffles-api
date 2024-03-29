<?php

use App\Http\Controllers\SubscriptionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\app\Http\Controllers\SubscriptionController as ControllersSubscriptionController;
use Modules\Admin\app\Http\Controllers\UserController;

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

Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    Route::resource('subscriptions',ControllersSubscriptionController::class);
    Route::get('authentication/user', [UserController::class, 'userRaffles']);
    Route::resource('users',UserController::class);
    Route::patch('user/raffles/auth/{id}', [UserController::class,'updateAuth']);
    Route::get('user/authorization/raffles',[UserController::class,'authRafflesPending']);
    Route::get('user/authorization/raffles/{id}',[UserController::class,'authRafflePending']);
    Route::patch('user/authorization/raffles/{id}',[UserController::class,'authRafflesPendingUpdate']);

});

