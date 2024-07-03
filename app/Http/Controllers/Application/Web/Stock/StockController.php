<?php

namespace App\Http\Controllers\Application\Web\Stock;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockProductsCard;
use App\Services\StockServices;
use Illuminate\Http\Request;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index( Request $request)
    {
        $stockCards = StockProductsCard::with(['product', 'stock'])
        ->when($request->name || $request->code, function ($query) use ($request) {
            return $query->whereHas('product', function ($query) use ($request) {
                if (!empty($request->code)) {
                    $query->where('code', 'like', '%' . $request->code . '%');
                }
                if (!empty($request->name)) {
                    $query->where('name', 'like', '%' . $request->name . '%');
                }
            });
        })
        ->orderBy('created_at','desc')
        ->paginate(10);

        // dd($stockCards);

        return view('application.stock.index',[
            'active_page' => 'stocks',
            'stockCards' => $stockCards,
            'search_terms' => [
                'code' => $request->code,
                'name' => $request->name
            ]
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $products = Product::where('is_active', true)->get();
        return view('application.stock.create',[
            'active_page' => 'stocks',
            'products' => $products
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate
        $validation_rules = [
            'type' => 'required',
            'product_id' => 'required',
            'quantity' => 'required|numeric',
            'uom' => 'required',
        ];

        $this->validate($request,$validation_rules);

        $stockService = new StockServices();
        $storeStockCard =$stockService->storeStockCard($request);

        if($storeStockCard['success']){
            return redirect()->route('application.stocks.index')->with('success_message', $storeStockCard['message']);
        } else {
            return redirect()->back()->with('error_message', $storeStockCard['message']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
