<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PayeeController;
use App\Http\Controllers\PayeePatternsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryPatternController;
use App\Http\Controllers\PlaidController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('/plaid')->group(function(){
    Route::get('/create_link_token',[PlaidController::class, 'create_link_token']);
    Route::post('/exchange_public_token',[PlaidController::class, 'exchange_public_token']);
    Route::get('/is_account_connected',[PlaidController::class, 'is_account_connected']);
    Route::get('/get_trans',[PlaidController::class, 'get_trans']);
});
