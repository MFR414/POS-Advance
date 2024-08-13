<?php

use App\Http\Controllers\Application\Web\Setting\SettingController;
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

Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', [SettingController::class,'index'])->name('index');
    Route::put('/update', [SettingController::class, 'update'])->name('update');
});