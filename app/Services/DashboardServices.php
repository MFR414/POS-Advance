<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use DB;

class DashboardServices
{

    public function getDashboardData() {

        $transaction = $this->getTransactionData();
        $income = $this->getIncomeData();
        $mostSellingItems = $this->getMostSellingItem();
        $totalSelledItems = $this->getTotalSelledItem();

        return $data = [
            'type' => 'yearly',
            'transaction' => $transaction,
            'income' => $income,
            'most_selling_items' => $mostSellingItems,
            'total_selled_items' => $totalSelledItems,
        ];
    }

    public function getMonthlyDashboardData() {
        
        $transaction = $this->getMonthlyTransactionData();
        $income = $this->getMonthlyIncomeData();

        return $data = [
            'type' => 'monthly',
            'transaction' => $transaction,
            'income' => $income,
            'month' => Carbon::now()->format('d/m/Y')
        ];
    }

    public function generateDatesInCurrentMonth($format = 'Y-m-d') {
        // get current date
        $currDate = Carbon::now();

        //get the first day and the last day of current month
        $startOfMonth = $currDate->copy()->startOfMonth();
        $endOfMonth = $currDate->copy()->endOfMonth();

        //generate the period between the first and last days of current month
        $period = CarbonPeriod::create($startOfMonth,$endOfMonth);

        //create an array to hold the dates
        $datesInMonth = [];

        //iterate over the period and add each date to the array
        foreach($period as $date){
            $datesInMonth[] = $date->format($format); // Format the date as needed
        }

        // return the array of dates
        return $datesInMonth;
    }

    public function getTransactionData() {
        $transaction = [];
        $transaction['count_all_time'] = Transaction::where('transaction_status', 'Sudah Dibayar')->count();

        $labels = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $data = [];
        $currentYear = Carbon::now()->year;

        for ($i = 1; $i <= 12; $i++) { // Loop from 1 to 12 for months
            // Create the start and end dates for the month
            $startDate = Carbon::create()->day(1)->month($i)->year($currentYear)->startOfMonth();
            $endDate = Carbon::create()->day(1)->month($i)->year($currentYear)->endOfMonth();
        
            // Count the transactions for the current month
            $transactionCount = Transaction::where('transaction_status', 'Sudah Dibayar')
                                            ->whereMonth('transaction_date', $i)
                                            ->whereYear('transaction_date', $currentYear)
                                            ->count();
        
            // Add the count to the data array
            $data[] = $transactionCount;
        }

        $transaction['labels'] = $labels;
        $transaction['data'] = $data;
        $transaction['year'] = $currentYear;
        return $transaction;
    }

    public function getMonthlyTransactionData() {
        $transaction = [];

        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $transaction['count_monthly'] = Transaction::where('transaction_status', 'Sudah Dibayar')
                                        ->whereBetween('transaction_date',[$startOfMonth, $endOfMonth])
                                        ->count();
        
        $dateMonths = $this->generateDatesInCurrentMonth();
        $data = [];

        foreach ($dateMonths as $date) {
            $transactionCount = Transaction::where('transaction_status', 'Sudah Dibayar')
                                ->where('transaction_date', $date)
                                ->count();
            
            $data[] = $transactionCount;
        }

        $transaction['labels'] = $this->generateDatesInCurrentMonth('d/m/Y');
        $transaction['data'] = $data;

        return $transaction;
    }

    public function getIncomeData(){
        $income = [];
        $income['sum_all_time'] = Transaction::where('transaction_status', 'Sudah Dibayar')->sum('final_total_after_additional');
        $income['sum_all_time'] = number_format($income['sum_all_time'], 0, ',', '.');

        $labels = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $data = [];
        $currentYear = Carbon::now()->year;

        for ($i = 1; $i <= 12; $i++) { // Loop from 1 to 12 for months
            // Create the start and end dates for the month
            $startDate = Carbon::create()->day(1)->month($i)->year($currentYear)->startOfMonth();
            $endDate = Carbon::create()->day(1)->month($i)->year($currentYear)->endOfMonth();
        
            // Count the transactions for the current month
            $incomeSum = Transaction::where('transaction_status', 'Sudah Dibayar')
                                            ->whereMonth('transaction_date', $i)
                                            ->whereYear('transaction_date', $currentYear)
                                            ->sum('final_total_after_additional');
        
            // Add the count to the data array
            // $data[] = number_format($incomeSum, 0, ',', '.'); 
            $data[] = $incomeSum;
        }

        $income['labels'] = $labels;
        $income['data'] = $data;
        $income['year'] = $currentYear;
        return $income;
    }

    public function getMonthlyIncomeData(){
        $income = [];

        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $income['sum_monthly'] = Transaction::where('transaction_status', 'Sudah Dibayar')
                                ->whereBetween('transaction_date',[$startOfMonth,$endOfMonth])
                                ->sum('final_total_after_additional');
        $income['sum_monthly'] = number_format($income['sum_monthly'], 0, ',', '.');

        $dateMonths = $this->generateDatesInCurrentMonth();
        $data = [];

        foreach($dateMonths as $date){
            $transactionCount = Transaction::where('transaction_status', 'Sudah Dibayar')
                                ->where('transaction_date', $date)
                                ->sum('final_total_after_additional');

            $data[] = $transactionCount;
        }

        $income['labels'] = $months = $this->generateDatesInCurrentMonth('d/m/Y');
        $income['data'] = $data;

        return $income;
    }

    public function getMostSellingItem() {
        $mostSellingItemsArray = [];

        // get 10 most selling items
        $mostSellingItems = TransactionDetail::select('item_name', DB::raw('count(*) as total_sold'))
                        ->groupBy('item_name')
                        ->orderBy('total_sold', 'desc')
                        ->take(10)
                        ->get();

        $mostSellingItemsArray['items_name'] = $mostSellingItems->pluck('item_name')->toArray();
        $mostSellingItemsArray['items_count'] = $mostSellingItems->pluck('total_sold')->toArray();
        
        return $mostSellingItemsArray;
    }

    public function getTotalSelledItem() {
        $totalSelledItemsArray = [];

        // get 10 most selling items
        $totalSelledItems = DB::table('transaction_details')
                            ->select('item_name', 'item_quantity_unit', DB::raw('SUM(item_quantity) as total_item_sold'))
                            ->whereNull('deleted_at')
                            ->groupBy('item_name', 'item_quantity_unit')
                            ->orderBy('total_item_sold', 'desc')
                            ->limit(50)
                            ->get();

        $totalSelledItemsArray['items_name'] = $totalSelledItems->map(function ($item) {
            return $item->item_name . ' (' . $item->item_quantity_unit . ')';
        })->toArray();

        $totalSelledItemsArray['items_count'] = $totalSelledItems->pluck('total_item_sold')->toArray();
        
        return $totalSelledItemsArray;
    }
}