<?php

use App\Http\Controllers\Application\Web\Users\AdminsController;
use App\Http\Controllers\Application\Web\Users\CashiersController;
use Illuminate\Support\Facades\Route;

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

Route::prefix('users')->name('users.')->group(function () {
    Route::resource('admins', AdminsController::class);
    Route::resource('cashiers', CashiersController::class);
});
