<?php

namespace App\Http\Controllers\Application\Web\Report;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockProducts;
use App\Models\StockProductsCard;
use Illuminate\Http\Request;
use DateTime;

class StockReportController extends Controller
{
    
    /**
     * Index function for the StockReportController.
     *
     * @param Request $request The HTTP request object.
     * @return void
     */
    public function index(Request $request){
        $products = Product::with('stock')->orderBy('code', 'asc');

        if(!empty($request->code)){
            $products = $products->where('code', 'like', '%'.$request->code.'%');
        }

        if(!empty($request->name)){
            $products = $products->where('name', 'like', '%'.$request->name.'%');
        }

        $products = $products->paginate(20);
        
        return view('application.report.stock.index', [
            'active_page' => 'reports',
            'active_subpage' => 'stocks',
            'products' => $products,
            'search_terms' => [
                'code' => $request->code,
                'name' => $request->name
            ]
        ]);
    }

    public function history($productId){
        $stockCards = StockProductsCard::where('product_id', $productId)->paginate(10);

        return view('application.report.stock.detail', [
            'active_page' => 'reports',
            'active_subpage' => 'stocks',
            'stockCards' => $stockCards,
        ]);
    }

    /**
     * Generates a report based on the provided filter criteria.
     *
     * @param Request $request The HTTP request object.
     * @return mixed The generated PDF report.
     */
    public function generateReport(Request $request)
    {
        // Get filter criteria from the request
        $code = $request->input('code');
        $name = $request->input('name');

        $products = Product::orderBy('name', 'asc')->with('stock');

        if (!empty($request->code) && $request->code !== 'undefined') {
            $products = $products->where('code', 'like', '%'.$request->code.'%');
        }

        if (!empty($request->name) && $request->name !== 'undefined') {
            $products = $products->where('name', 'like', '%'.$request->name.'%');
        }

        $products = $products->get();

        // Array to hold grouped stock cards
        $groupedStockCards = [];

        foreach ($products as $product) {
            if (!empty($product->stock)) {
                // Ambil semua data yang diperlukan
                $stockCards = StockProductsCard::where('product_id', $product->id)
                    ->orderBy('created_at', 'asc')
                    ->with('product')
                    ->get();

                // Kelompokkan data berdasarkan produk
                $groupedStockCards[$product->name] = $stockCards;
            }
        }

        // Debugging grouped stock cards
        // dd($groupedStockCards);

        // Pass the grouped data to the view
        return view('application.report.stock.report-pdf', compact('groupedStockCards'));
    }
}
