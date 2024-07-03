<?php

use App\Http\Controllers\Application\Web\Transaction\TransactionController;
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

Route::resource('transactions', TransactionController::class);
Route::prefix('transactions.payment')->name('transactions.payment.')->group(function () {
    Route::get('transactions/{transaction}/payment', [TransactionController::class, 'showPaymentForm'])->name('form');
    Route::post('transactions/{transaction}/payment/submit', [TransactionController::class, 'submitPayment'])->name('submit');    
});

