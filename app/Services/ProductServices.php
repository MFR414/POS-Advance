<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockProducts;
use App\Models\StockProductsCard;
use Carbon\Carbon;
use DB;

class ProductServices
{
    public function saveProduct($request) {
        $response = [
            'success' => false,
            'message' => '',
        ];

        try {
            //code...
            $product = Product::where('code', 'like', '%'.$request->code.'%')
                            ->where('name','like','%'.$request->name.'%')->with('stock')
                            ->first(); 

            if (empty($product)) {
                $product = new Product();
                // Set default status to active for new products
                $product->is_active = true;
            }
            
            $saveProduct = DB::transaction(function () use ($product, $request) {
                $product->code = $request->code ?? $product->code;
                $product->name = $request->name ?? $product->name;
                $product->price = $request->price ?? $product->price;
                $product->uom = $request->uom ?? $product->uom;
                $product->description = $request->description ?? $product->description;
            
                // Update status based on the request input
                if ($request->has('is_active')) {
                    $product->is_active = $request->is_active == '1' ? true : false;
                }

                $product->save();

                if($request->has('initial_stock') && $request->filled('initial_stock')) {
                    $stockCard = new StockProductsCard();
                    
                    $stockCard->type = 'pemasukan';
                    $stockCard->product_id = $product->id;
                    $stockCard->stock_id = $product->stock->id ?? null;
                    $stockCard->quantity = $request->initial_stock;
                    $stockCard->stock_before = $product->stock->quantity ?? 0;
                    $stockCard->stock_after = $request->initial_stock;
                    $stockCard->uom = $product->uom ?? $request->uom;
                    $stockCard->is_executed = 0;
                    $stockCard->save();

                    $stockService = new StockServices();
                    $stockService->updateStockCard($stockCard->id);
                };

                return $product;
            });

            if(!empty($saveProduct) && $saveProduct->id){
                $response['success'] = true;
                $response['message'] = 'Berhasil menambahkan / mengubah produk '.$saveProduct->code.' - '.$saveProduct->name;
                $response['data'] = $saveProduct; 
            } else {
                $response['success'] = false;
                $response['message'] = 'gagal menambahkan / mengubah produk '.$product->code.' - '.$product->name;
            }
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = 'gagal menambahkan / mengubah produk '.$product->code.' - '.$product->name;
            $response['error'] = $e->getMessage();
        }

        return $response;
    }

    public function deleteProduct($id) {
        $response = [
            'success' => false,
            'message' => '',
        ];

        $product = Product::find($id);
        
        $deletedProduct = DB::transaction(function () use ($product) {
            if(!empty($product)) {
                $tempProduct = $product->replicate();
                $product->delete();
            } else {
                $tempProduct = null;
            }
            
            return $tempProduct;
        });

        if(!empty($tempProduct) && $tempProduct->id){
            $response['success'] = true;
            $response['message'] = 'Berhasil menghapus produk '.$tempProduct->code.' - '.$tempProduct->name;
            $response['data'] = $tempProduct; 
        } else {
            $response['success'] = false;
            $response['message'] = 'gagal menghapus produk '.$product->code.' - '.$product->name;
        }

        return $response;
    }
}