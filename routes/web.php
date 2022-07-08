<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function(){
    return view('welcome');
});

Route::get('/inside', function () {
    return view('vue');
});

/*
1. Go to localhost:8000/test
2. web.php routes are checked for a get('/test'). If found, looks at the parameter in view('xxxx').
3. looks in /resources/views/xxxx.blade.php
4. looks in xxxx.blade.php for a div with an id (example <div id="yyyy"></div>)
5. gets html from Yyyy.vue
*/
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
