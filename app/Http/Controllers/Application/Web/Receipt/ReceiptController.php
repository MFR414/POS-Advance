<?php

namespace App\Http\Controllers\Application\Web\Receipt;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\TransactionServices;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use PDF, DB;

class ReceiptController extends Controller
{
   /**
     * Generate receipt
     */
    public function generateReceipt(string $id)
    {
        $transaction = Transaction::find($id);
        
        if(!empty($transaction)){
       
            $transactionDetails = TransactionDetail::where('transaction_number', $transaction->transaction_number)->get();

            if(!empty($transactionDetails)){
                //prepare the data
                $transaction->items = $transactionDetails;
                $transaction->formatted_create_date = Carbon::parse($transaction->created_at)->format('d/m/Y H:i');
                $transaction->sales_code = ucwords($transaction->sales_code);
                $transaction->customer_name = ucwords($transaction->customer_name);
                $transaction->customer_address = ucwords($transaction->customer_address);
                $transaction->creator = strtoupper($transaction->creator);

                // $transactionServices = new TransactionServices();
                // $transaction->terbilang = $transactionServices->generatePenyebut($transaction->final_total_after_additional);

                // convert into PDF format
                $pdf = PDF::loadView('application.receipt.receipt-layout', ['data' => $transaction])
                      ->setPaper([0, 0, 84.65, 164.41], 'portrait');// 30mm x 58mm in points
                // $pdf = PDF::loadView('application.receipt.receipt-layout', ['data' => $transaction])
                //         ->setPaper([0, 0, 164.41, 85.04], 'landscape');
        
                // Define the file name dynamically (e.g., using an order number)
                $fileName = 'receipts_' . $transaction->transaction_number . '.pdf';

               // Directory path
                $directoryPath = public_path('receipts');

                // Ensure the invoices directory exists within the public directory
                if (!File::exists($directoryPath)) {
                    File::makeDirectory($directoryPath, 0777, true, true);
                }

                // File path
                $filePath = public_path('receipts/' . $fileName);

                // Check if the file exists
                if (File::exists($filePath)) {
                    // Delete the existing file
                    File::delete($filePath);
                }

                // Save the PDF to the public directory
                $saved = File::put($filePath, $pdf->output());

                if ($saved) {
                    $updateTransaction = DB::transaction(function () use ($transaction, $fileName){
                        $updateTransaction = Transaction::find($transaction->id);
                        $updateTransaction->receipt_filename = $fileName;
                        $updateTransaction->save();
                        return $updateTransaction;
                    });
                    if(!empty($updateTransaction->receipt_filename)){
                        return redirect()
                        ->back()
                        ->with('success_message', 'Struk nomor '.$transaction->transaction_number.' berhasil dibuat.');
                    } else {
                        return redirect()
                        ->back()
                        ->with('error_message', 'Kesalahan dalam pembuatan struk, Silahkan coba lagi dalam beberapa saat atau hubungi admin');
                    }
                } else {
                    return redirect()
                    ->back()
                    ->with('error_message', 'Kesalahan saat menyimpan invoice, Silahkan coba lagi dalam beberapa saat atau hubungi admin');
                }



                // // Check if the file exists
                // if (Storage::disk('public')->exists('invoices/' . $fileName)) {
                //     // Return a response to download the file
                //     return Storage::disk('public')->download('invoices/' . $fileName, $fileName);
                // } else {
                //     // File not found, handle the error
                //     // For example, redirect back with an error message
                //     return redirect()->back()->with('error', 'File not found!');
                // }
            } else {
                return redirect()
                ->back()
                ->with('error_message', 'Item tidak ditemukan. silahkan coba lagi dalam beberapa saat atau hubungi admin');
            }
        } else {
            return redirect()
                ->back()
                ->with('error_message', 'Kesalahan tidak diketahui, Silahkan coba lagi dalam beberapa saat atau hubungi admin');
        }
    }

    /**
     * Download receipt
     */
    public function downloadReceipt(string $id){
        $transaction = Transaction::find($id);
        $filePath =   $filePath = public_path('receipts/' . $transaction->receipt_filename);

        // Check if the file exists
        if (File::exists($filePath)){
            // Return a response to download the file
            return response()->download($filePath);
        } else {
            // File not found, handle the error
            // For example, redirect back with an error message
            return redirect()->back()->with('error_message', 'File tidak ditemukan! silahkan coba dalam beberapa saat atau silahkan buat ulang invoice terlebih dahulu.');
        }
    }

    /**
     * check layout receipt
     */

    public function checkReceiptlayout(string $id){
        $transaction = Transaction::find($id);
        $transactionDetails = TransactionDetail::where('transaction_number', $transaction->transaction_number)->get();
        $transaction->items = $transactionDetails;
        
        $transaction->formatted_create_date = Carbon::parse($transaction->created_at)->format('d/m/Y H:i');
        $transaction->sales_code = ucwords($transaction->sales_code);
        $transaction->customer_name = ucwords($transaction->customer_name);
        $transaction->customer_address = ucwords($transaction->customer_address);
        $transaction->creator = strtoupper($transaction->creator);

        $transactionServices = new TransactionServices();
        $transaction->terbilang = $transactionServices->generatePenyebut($transaction->final_total_after_additional);
        // dd($transaction);
        
        return view('application.receipt.receipt-layout', ['data' => $transaction]);
    }
}
