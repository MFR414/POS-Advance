<?php

namespace App\Http\Controllers\Application\Web\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\TransactionServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $transactions = Transaction::orderBy('transaction_date', 'desc');

        if(!empty($request->transaction_number)){
            $transactions = $transactions->where('transaction_number', 'LIKE', '%'.$request->transaction_number.'%');
        }

        if(!empty($request->customer_name)){
            $transactions = $transactions->where('customer_name', 'LIKE', '%'.$request->customer_name.'%');
        }

        $transactions = $transactions->paginate(20);

        return view('application.transaction.index',[
            'active_page' => 'transactions',
            'transactions' => $transactions,
            'search_terms' => [
                'transaction_number' => $request->transaction_number,
                'customer_name' => $request->customer_name
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transactionServices = new TransactionServices();
        $transactionNumber = TransactionServices::getTransactionNumber();
        $date = Carbon::now()->format('Y-m-d');
        $products = $transactionServices->getProductList();

        if(!empty($products)){
            return view('application.transaction.create',[
                'active_page' => 'transactions-create',
                'transaction_date' => $date,
                'transaction_number' => $transactionNumber['number'],
                'products' => $products
            ]);
        } else {
            return redirect()->back()->with('error_message', 'Maaf stok semua produk masih kosong. silahkan masukkan stok produk terlebih dahulu !');
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         // Validate
         $validation_rules = [
            'customer_name' => 'required',
        ];

        // dd($request->all());

        $this->validate($request,$validation_rules);
        $transaction = new TransactionServices;
        $transaction = $transaction->saveTransaction($request);
        

        return redirect()
        ->route('application.transactions.payment.form', $transaction->id)
        ->with('success_message', 'Berhasil menambahkan transaksi '.$transaction->transaction_number.' !');

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = Transaction::find($id);
        $transaction->details = TransactionDetail::where('transaction_number',$transaction->transaction_number)->get();

        return view('application.transaction.show',[
            'active_page' => 'transactions',
            'transaction' => $transaction
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $transaction = Transaction::where('id',$id)->first();
        $transactionServices = new TransactionServices();
        if(empty($transaction)){
            return redirect()->back()->with('error_message', 'Transaksi tidak ditemukan, silahkan coba kembali beberapa saat atau hubungi admin !');
        } else {
            // Format transaction_date attribute to Y-m-d
            $formatted_date_input = Carbon::parse($transaction->transaction_date)->format('Y-m-d');
            $transaction->formatted_transaction_date_input = $formatted_date_input;
            $items = TransactionDetail::where('transaction_number',$transaction->transaction_number)->get();
            $products = $transactionServices->getProductList();
        }

        // dd($transaction);
        return view('application.transaction.edit',[
            'active_page' => 'transactions',
            'transaction' => $transaction,
            'products' => $products,
            'items' => json_encode($items) //encode items to JSON
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validate
         $validation_rules = [
            'customer_name' => 'required',
        ];

        $this->validate($request,$validation_rules);

        // dd($request->all()); 

        $checkTransaction = Transaction::find($id);
        if($checkTransaction->transaction_status == 'Belum Dibayar' && !empty($checkTransaction)){
            $transaction = new TransactionServices;
            $transaction = $transaction->updateTransaction($request);
        } else {
            return redirect()->back()->with('error_message', 'Transaksi tidak ditemukan, silahkan coba kembali beberapa saat atau hubungi admin !');
        }

        return redirect()
        ->route('application.transactions.index')
        ->with('success_message', 'Berhasil merubah transaksi '.$transaction->transaction_number.' !');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for submit payment.
     * 
     */
    public function showPaymentForm(string $id){
        $transaction = Transaction::find($id);
        $transaction->details = TransactionDetail::where('transaction_number',$transaction->transaction_number)->get();

        return view('application.transaction.payment',[
            'active_page' => 'transactions',
            'transaction' => $transaction
        ]);
    }

    /**
     * submit the payment
     */

    public function submitPayment(Request $request){
        // Validate
        $validation_rules = [
            'transaction_number' => 'required',
            'payment_type' => 'required',
            'cash' => 'required_if:payment_type,cash',
        ];

        $response = [
            'success' => false,
            'message' => '', 
        ];

        $this->validate($request, $validation_rules);

        $transaction = Transaction::where('transaction_number', $request->transaction_number)->first();

        if ($transaction === null) {
            $response['message'] = 'Maaf, kami tidak menemukan transaksi tersebut. Mohon coba beberapa saat lagi. Jika kesalahan masih berlanjut, silakan hubungi administrator kami.';
        } else { 
            $transactionServices = new TransactionServices();
            $payment = $transactionServices->updateTransactionAfterpayment($request, $transaction);

            if ($payment['success']) {
                $response['success'] = true;
                $response['message'] = 'Transaksi '.$transaction->transaction_number.' telah dibayar. Terima kasih';
            } else {
                $response['message'] = 'Terjadi kesalahan saat melakukan pembayaran. Silakan coba lagi atau hubungi administrator kami.';
            }
        }

        if ($response['success']) {
            return redirect()
                ->route('application.transactions.index')
                ->with('success_message', $response['message']);
        } else {
            return redirect()
                ->route('application.transactions.index')
                ->with('error_message', $response['message']);
        }
    }
}
