<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    public function index() {}

    public function create() {}

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'quantity' => 'required|numeric|min:1',
            'remarks' => 'nullable|string|max:255',
        ]);

        // Record stock transaction
        $stock_in = new StockTransaction();
        $stock_in->product_id = $request->product_id;
        $stock_in->warehouse_id = $request->warehouse_id;
        $stock_in->transaction_type = 'in';
        $stock_in->quantity = $request->quantity;
        $stock_in->remarks = $request->remarks;
        $stock_in->save();

        /* Update or create product stock */
        $productStock = ProductStock::firstOrNew([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->warehouse_id,
        ]);
        $productStock->quantity += $request->quantity;
        $productStock->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Stock added successfully',
            'data' => $stock_in,
        ], 201);
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
