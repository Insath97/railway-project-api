<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product_StocksResource;
use App\Models\ProductStock;
use Illuminate\Http\Request;

class Product_StockController extends Controller
{
    public function index()
    {
        $product_stock = ProductStock::where('delete_status', 1)->get();

        if ($product_stock->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return Product_StocksResource::collection($product_stock);
    }

    public function create() {}

    public function store(Request $request)
    {
        $product_stock = new ProductStock();
        $product_stock->product_id = $request->product_id;
        $product_stock->warehouse_idquantity = $request->warehouse_idquantity;
        $product_stock->quantity = $request->quantity;
        $product_stock->save();

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $product_stock,
        ], 200);
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
