<?php

namespace App\Console\Commands;

use App\Models\StockProducts;
use App\Models\StockProductsCard;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use DB;

class UpdateStockFromStockCard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-stock-from-stock-card';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update stock from stock card';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Log a message indicating that the command is being executed
        Log::info('app:update-stock-from-stock-card is being executed.');

        // get all unexecuted stock card
        $stockCards = StockProductsCard::where('is_executed', 0)->get();
    
        // loop all unexecuted stock card
        foreach($stockCards as $stockCard) {
            try {
                DB::transaction(function () use ($stockCard){
                    $stock = StockProducts::where('product_id', $stockCard->product_id)->first();
                    if(empty($stock)) {
                        $stock = new StockProducts();
                    }
        
                    $stock->product_id = $stockCard->product_id;

                    if($stockCard->type == "pemasukan") {
                        $stock->quantity += $stockCard->quantity;
                    } else {
                        $stock->quantity -= $stockCard->quantity;
                    }

                    $stock->uom = $stockCard->uom;
                    $stock->save();
        
                    $stockCard->is_executed = 1;
                    $stockCard->stock_id = $stock->id;
                    $stockCard->stock_after = $stock->quantity;
                    $stockCard->executed_at = Carbon::now();
                    $stockCard->save();
                });

                Log::info('Stock card '.$stockCard->id.' for product '.$stockCard->product_id.' updated!');
                $stockCard->exec_info =  'Stock card '.$stockCard->id.' for product '.$stockCard->product_id.' updated!';
                $stockCard->save(); 
            } catch (\Exception $e) {
                // Log the error message
                Log::error('Error updating stock from stock card', [
                    'stockCardId' => $stockCard->id,
                    'productId' => $stockCard->product_id,
                    'quantity' => $stockCard->quantity,
                    'error' => $e->getMessage(),
                    'stackTrace' => $e->getTraceAsString(),
                ]);

                $stockCard->exec_info =  'Error updating stock from stock card: '.$e->getMessage();
                $stockCard->save(); 
            };

        }
    }
}
