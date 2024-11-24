<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductsResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index()
    {
        $products = Product::with(['mainCategory','subCategory','unitType','productStocks'])->where('delete_status', 1)->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return ProductsResource::collection($products);
    }

    public function create() {}

    public function store(StoreProductRequest $request)
    {
        $products = new Product();
        $products->main_category_id = $request->main_category_id;
        $products->sub_category_id = $request->sub_category_id;
        $products->unitType_id = $request->unitType_id;
        $products->code = $request->code;
        $products->name = $request->name;
        $products->color = $request->color;
        $products->size = $request->size;
        $products->low_stock_threshold = $request->low_stock_threshold;
        $products->description = $request->description;
        $products->save();

        return response()->json([
            'message' => 'Product created successfully',
            'data' => $products,
        ], 200);
    }

    public function show(string $products)
    {
        $products = Product::with('productStocks')->find($products);

        return response()->json([
            'data' => new ProductsResource($products),
        ], 200);
    }

    public function edit(string $id) {}

    public function update(UpdateProductRequest $request, string $id)
    {
        $products = Product::findOrFail($id);
        $products->main_category_id = $request->main_category_id;
        $products->sub_category_id = $request->sub_category_id;
        $products->unitType_id = $request->unitType_id;
        $products->code = $request->code;
        $products->name = $request->name;
        $products->color = $request->color;
        $products->size = $request->size;
        $products->low_stock_threshold = $request->low_stock_threshold;
        $products->description = $request->description;
        $products->save();

        return response()->json([
            'message' => 'Product Update successfully',
            'data' => $products,
        ], 200);
    }

    public function destroy(string $id)
    {
        $products = Product::findOrFail($id);
        $products->delete_status = 0;
        $products->save();

        return response()->json(
            [
                'message' => 'Product deleted successfully',
            ],
            200
        );
    }

    public function getProducts(Request $request)
    {
        $product = Product::with(['mainCategory','subCategory','unitType'])->where('code', $request->code)
            ->orWhere('name', $request->name)
            ->first();

        if ($product) {
            return response()->json([
                'status' => 'success',
                'message' => 'Product details retrieved successfully',
                'data' => $product,
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Product not found',
        ], 404);
    }
}
