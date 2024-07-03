<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockProducts;
use App\Models\StockProductsCard;
use Carbon\Carbon;
use DB;

class StockServices
{
    public function storeStockCard($request){
        $response = [];
        $product = Product::with('stock')->find($request->product_id);

        if($product->stock->quantity >= $request->quantity){
            $stockCard = StockProductsCard::create([
                'type' => $request->type,
                'quantity' => $request->quantity,
                'stock_before' => $product->stock->quantity,
                'stock_after' => $product->stock->quantity,
                'uom' => $request->uom,
                'description' => $request->description,
                'product_id' => $product->id,
                'price' => $product->price,
                'is_executed' => false,
                'created_at' => Carbon::now(),
            ]);
    
            if(empty($stockCard->id)){
                $response['success'] = false;
                $response['message'] = 'Failed to store stock card!';
            } else {
                // update stock
                $updateStockCard = $this->updateStockCard($stockCard->id);
    
                if($updateStockCard->is_executed){
                    $response['success'] = true;
                    $response['data'] = $updateStockCard;
                    $response['message'] = 'Success updating stock card!';
                } else {
                    $response['success'] = false;
                    $response['message'] = 'Failed to update stock!';
                }
            }
        } else {
            $response['success'] = false;
            $response['message'] = 'Failed to update stock, insufficient stock!';
        }
        
        return $response;
    }

    public function updateStockCard($stockCardId){
        // get unexecuted stock card form id
        $stockCard = StockProductsCard::where('is_executed', 0)
                   ->where('id', $stockCardId)
                   ->first();

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
                $stockCard->exec_info =  'Stock card '.$stockCard->id.' for product '.$stockCard->product_id.' updated!'; 
                $stockCard->save();
            });
        } catch (\Exception $e) {
            $stockCard->exec_info =  'Error updating stock from stock card: '.$e->getMessage(); 
            $stockCard->save();
        };

        return $stockCard;
    }
}