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
        $product_stock = ProductStock::all();

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
        $product_stock->warehouse_id = $request->warehouse_i;
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

    public function getWarehouseProducts(string $id, string $office, string $warehouse)
    {
        $product_stock = ProductStock::with('product', 'warehouse')->where('warehouse_id', $id)->get();

        if ($product_stock->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return response()->json([
            'message' => 'Products fetched successfully.',
            'office' => $office,
            'warehouse' => $warehouse,
            'products' => $product_stock,
        ], 200);
    }

    public function getProductsByMainCategory(string $main_category, string $office, string $warehouse)
    {
        $product_stock = ProductStock::with('product', 'warehouse')->where('warehouse_id', $warehouse)->whereHas('product', function ($query) use ($main_category) {
            $query->where('main_category_id', $main_category);
        })->get();

        if ($product_stock->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return response()->json([
            'message' => 'Products fetched successfully.',
            'office' => $office,
            'warehouse' => $warehouse,
            'products' => $product_stock,
        ], 200);
    }
}
