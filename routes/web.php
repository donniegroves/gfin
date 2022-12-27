<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryPatternController;
use App\Http\Controllers\PayeeController;
use App\Http\Controllers\PayeePatternsController;
use App\Http\Controllers\PlaidController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\TransactionController;
use App\Models\ImportHistory;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// API-like Requests
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::prefix('/reqs')->group(function(){
        Route::get('/transactions',[TransactionController::class, 'index']);
        Route::get('/transactions/stats',[TransactionController::class, 'get_stats']);
        Route::post('/transactions/store', [TransactionController::class, 'store']);
        Route::post('/transactions/import',[TransactionController::class, 'import']);
        Route::get('/transactions/history',[ImportHistory::class, 'get']);
        Route::put('/transactions/update/{id}', [TransactionController::class, 'update']);
        Route::delete('/transactions/destroy/{id}', [TransactionController::class, 'destroy']);

        Route::get('/settings',[SettingsController::class, 'index']);
        Route::post('/settings', [SettingsController::class, 'store']);

        Route::get('/payees',[PayeeController::class, 'index']);
        Route::post('/payees/store', [PayeeController::class, 'store']);
        Route::put('/payees/update/{id}', [PayeeController::class, 'update']);
        Route::delete('/payees/destroy/{id}', [PayeeController::class, 'destroy']);

        Route::get('/payeepatterns',[PayeePatternsController::class, 'index']);
        Route::post('/payeepatterns/store', [PayeePatternsController::class, 'store']);
        Route::put('/payeepatterns/update/{id}',[PayeePatternsController::class, 'update']);
        Route::delete('/payeepatterns/destroy/{id}', [PayeePatternsController::class, 'destroy']);

        Route::get('/categories',[CategoryController::class, 'index']);
        Route::post('/categories/store', [CategoryController::class, 'store']);
        Route::put('/categories/update/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/destroy/{id}', [CategoryController::class, 'destroy']);

        Route::get('/categorypatterns/{category_id?}',[CategoryPatternController::class, 'index']);
        Route::post('/categorypatterns/store', [CategoryPatternController::class, 'store']);
        Route::put('/categorypatterns/update/{id}',[CategoryPatternController::class, 'update']);
        Route::delete('/categorypatterns/destroy/{id}', [CategoryPatternController::class, 'destroy']);

        Route::get('/plaid/create_link_token',[PlaidController::class, 'create_link_token']);
        Route::post('/plaid/exchange_public_token',[PlaidController::class, 'exchange_public_token']);
        Route::get('/plaid/is_account_connected',[PlaidController::class, 'is_account_connected']);
        Route::get('/plaid/unlink_account',[PlaidController::class, 'unlink_account']);
        Route::get('/plaid/transactions/import',[PlaidController::class, 'import_transactions']);
    });
});

// Protected Page Views
Route::group(['middleware' => ['auth:sanctum']], function(){
    Route::get('/dashboard', function () {return Inertia::render('Dashboard');})->name('dashboard');
    Route::get('/overview', function () {return Inertia::render('Overview');})->name('overview');
    Route::get('/transactions', function () {return Inertia::render('Transactions');})->name('transactions');
    Route::get('/import', function () {return Inertia::render('Import');})->name('import');
    Route::get('/calendar', function () {return Inertia::render('Calendar');})->name('calendar');
    Route::get('/settings', function () {return Inertia::render('Settings');})->name('settings');
    Route::get('/payees', function () {return Inertia::render('Payees');})->name('payees');
    Route::get('/categories', function () {return Inertia::render('Categories');})->name('categories');
});


require __DIR__.'/auth.php';
