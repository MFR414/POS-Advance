<?php

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

use App\Http\Controllers\Application\Web\Receipt\ReceiptController;
use Illuminate\Support\Facades\Route;

Route::prefix('receipts')->name('receipts.')->group(function () {
    Route::get('/receipts/{transaction}/generate', [ReceiptController::class, 'generateReceipt'])->name('generate');
    Route::get('/receipts/{transaction}/download', [ReceiptController::class, 'downloadReceipt'])->name('download');
    Route::get('/receipts/{transaction}/check', [ReceiptController::class, 'checkReceiptlayout'])->name('check');
});