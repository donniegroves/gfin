<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/* BEGIN INSIDE */
Route::get('/overview', function () {
    return Inertia::render('Overview');
})->middleware(['auth', 'verified'])->name('overview');

Route::get('/transactions', function () {
    return Inertia::render('Transactions');
})->middleware(['auth', 'verified'])->name('transactions');

Route::get('/import', function () {
    return Inertia::render('Import');
})->middleware(['auth', 'verified'])->name('import');

Route::get('/payees', function () {
    return Inertia::render('Payees');
})->middleware(['auth', 'verified'])->name('payees');

Route::get('/categories', function () {
    return Inertia::render('Categories');
})->middleware(['auth', 'verified'])->name('categories');

Route::get('/settings', function () {
    return Inertia::render('Settings');
})->middleware(['auth', 'verified'])->name('settings');

/* END INSIDE */

// Route::inertia('settings', 'Settings');
// Route::inertia('overview', 'Overview');

require __DIR__.'/auth.php';
