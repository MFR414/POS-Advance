<?php

use App\Http\Controllers\Application\Web\Invoice\InvoiceController;
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

Route::prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/invoices/{transaction}/generate', [InvoiceController::class, 'generateInvoice'])->name('generate');
    Route::get('/invoices/{transaction}/download', [InvoiceController::class, 'downloadInvoice'])->name('download');
    Route::get('/invoices/{transaction}/check', [InvoiceController::class, 'checkInvoicelayout'])->name('check');
});
