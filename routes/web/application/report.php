<?php

use App\Http\Controllers\Application\Web\Report\StockReportController;
use App\Http\Controllers\Application\Web\Report\TransactionReportController;
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


Route::prefix('reports')->name('reports.')->group(function () {
    Route::prefix('transactions')->name('transactions.')->group(function () {
        Route::get('/', [TransactionReportController::class,'index'])->name('index');
        Route::get('/export', [TransactionReportController::class,'generateReport'])->name('export');
        Route::get('/{report}', [TransactionReportController::class,'detail'])->name('show');
    });
    Route::prefix('stocks')->name('stocks.')->group(function () {
        Route::get('/', [StockReportController::class,'index'])->name('index');
        Route::get('/export', [StockReportController::class,'generateReport'])->name('export');
        Route::get('/{product_id}', [StockReportController::class,'history'])->name('show');
    });
});