<?php

namespace App\Http\Controllers\Application\Web\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use DateTime;
use Illuminate\Http\Request;
use PDF;

class ReportController extends Controller
{
    public function index(Request $request){
        // Get the current month and year
        $currentMonth = date('n');
        $currentYear = date('Y');

        // Default search terms
        $search_terms = [
            'transaction_month' => $request->input('month', $currentMonth),
            'transaction_year' => $request->input('year', $currentYear),
        ];

        $transactions =  Transaction::where('transaction_status','Sudah Dibayar')->orderBy('transaction_date', 'desc');

        // Filter by month
        if (!empty($request->month)) {
            $transactions->whereMonth('transaction_date', $request->month);
        }

        // Filter by year
        if (!empty($request->year)) {
            $transactions->whereYear('transaction_date', $request->year);
        }

        // // Filter by date
        // if (!empty($request->date)) {
        //     $transactions->whereDate('transaction_date', $request->date);
        // }


        // $transactions = $transactions->toSql();
        // dd($transactions);
        $transactions = $transactions->paginate(20);
        foreach ($transactions as $transaction) {
            $transactionDetails = TransactionDetail::where('transaction_number',$transaction->transaction_number)->get();
            $transaction->details = $transactionDetails;
        }

        return view('application.report.index', [
            'active_page' => 'report',
            'transactions' => $transactions,
            'search_terms' => $search_terms
        ]);
    }

    public function detail(string $id){
        
    }
    public function generateReport(Request $request)
    {
        // Get filter criteria from the request
        $month = $request->input('month');
        $year = $request->input('year');

        // Initialize the query
        $transaction = new Transaction;

        // Apply filters if provided
        if ($month) {
            $transaction->whereMonth('transaction_date', $month);
        }
        if ($year) {
            $transaction->whereYear('transaction_date', $year);
        }

        // Execute the query and get the results
        $transactions = $transaction->get();

        foreach($transactions as $transaction){
            $transactionDetail = TransactionDetail::where('transaction_number', $transaction->transaction_number)->get();
            $description = 'Pembelian ';
            $items = [];
        
            foreach ($transactionDetail as $detail) {
                $items[] = $detail->item_name;
            }
        
            $transaction->description = $description . implode(', ', $items);
            $transaction->items = $transactionDetail;
        }
        
        $formattedMonth = DateTime::createFromFormat('!m', $month)->format('F');

        // Share data to view
        $data = [
            'transactions' => $transactions,
            'search_terms' => [
                'transaction_month' => $formattedMonth,
                'transaction_year' => $year,
            ],
        ];

        // Load view and pass data to it
        $pdf = PDF::loadView('application.report.report-pdf', $data);


        // Download the PDF file
        return $pdf->download('laporan Transaksi ' . $formattedMonth . ' ' . $year . '.pdf');
    }
}
