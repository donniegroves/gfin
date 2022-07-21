<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\PayeeController;
use App\Http\Controllers\PayeePatternsController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryPatternController;

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

Route::get('/transactions',[TransactionController::class, 'index']);
Route::prefix('/transaction')->group(function(){
    Route::post('/store', [TransactionController::class, 'store']);
    Route::put('{id}', [TransactionController::class, 'update']);
    Route::delete('{id}', [TransactionController::class, 'destroy']);
});

Route::get('/payees',[PayeeController::class, 'index']);
Route::prefix('/payee')->group(function(){
    Route::post('/store', [PayeeController::class, 'store']);
    Route::put('{id}', [PayeeController::class, 'update']);
    Route::delete('{id}', [PayeeController::class, 'destroy']);
});

Route::get('/payeepatterns',[PayeePatternsController::class, 'index']);
Route::prefix('/payeepattern')->group(function(){
    Route::post('/store', [PayeePatternsController::class, 'store']);
    Route::put('{id}',[PayeePatternsController::class, 'update']);
    Route::delete('{id}', [PayeePatternsController::class, 'destroy']);
});

Route::get('/categories',[CategoryController::class, 'index']);
Route::prefix('/category')->group(function(){
    Route::post('/store', [CategoryController::class, 'store']);
    Route::put('{id}', [CategoryController::class, 'update']);
    Route::delete('{id}', [CategoryController::class, 'destroy']);
});

Route::get('/categorypatterns/{category_id?}',[CategoryPatternController::class, 'index']);
Route::prefix('/categorypattern')->group(function(){
    Route::post('/store', [CategoryPatternController::class, 'store']);
    Route::put('{id}',[CategoryPatternController::class, 'update']);
    Route::delete('{id}', [CategoryPatternController::class, 'destroy']);
});