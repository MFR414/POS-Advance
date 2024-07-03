<?php

namespace App\Http\Controllers\Application\Web\Product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\ProductServices;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $products = Product::with('stock')->orderBy('code', 'asc');

        if(!empty($request->code)){
            $products = $products->where('code', 'like', '%'.$request->code.'%');
        }

        if(!empty($request->name)){
            $products = $products->where('name', 'like', '%'.$request->name.'%');
        }

        $products = $products->paginate(10);

        // dd($products);

        return view('application.product.index',[
            'active_page' => 'products',
            'products' => $products,
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
        return view('application.product.create',[
            'active_page' => 'products'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate
        $validation_rules = [
            'code' => 'required|unique:products,code,NULL,id,deleted_at,NULL',
            'name' => 'required|unique:products,name,NULL,id,deleted_at,NULL',
            'price' => 'required|numeric',
            'uom' => 'required',
        ];

        $this->validate($request,$validation_rules);

        $productServices = new ProductServices();
        $saveProduct = $productServices->saveProduct($request);

        if($saveProduct['success']){
            return redirect()->route('application.products.index')->with('success_message', $saveProduct['message']);
        } else {
            return redirect()->back()->with('error_message', $saveProduct['message']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $product = Product::find($id);
        return view('application.product.edit',[
            'active_page' => 'products',
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         // Validate
         $validation_rules = [
            'code' => 'required',
            'name' => 'required',
            'price' => 'required|numeric',
            'uom' => 'required',
        ];

        $this->validate($request,$validation_rules);
        
        $productServices = new ProductServices();
        $updateProduct = $productServices->saveProduct($request);
        
        if($updateProduct['success']){
            return redirect()->route('application.products.index')->with('success_message', $updateProduct['message']);
        } else {
            return redirect()->back()->with('error_message', $updateProduct['message']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productServices = new ProductServices();
        $deleteProduct = $productServices->deleteProduct($id);

        if($deleteProduct['success']){
            return redirect()->route('application.products.index')->with('success_message', $deleteProduct['message']);
        } else {
            return redirect()->back()->with('error_message', $deleteProduct['message']);
        }
    }
}
