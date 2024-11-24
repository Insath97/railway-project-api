<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductStock;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockOutController extends Controller
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

        /*  $user = auth()->guard('admin')->user();

        if ($user->warehouse_id && $user->warehouse_id != $request->warehouse_id) {
            return response()->json(['status' => 'error', 'message' => 'Unauthorized access to this warehouse'], 403);
        } */

        $productStock = ProductStock::where([
            'product_id' => $request->product_id,
            'warehouse_id' => $request->warehouse_id,
        ])->first();

        if (!$productStock || $productStock->quantity < $request->quantity) {
            return response()->json(['status' => 'error', 'message' => 'Insufficient stock available'], 400);
        }

        // Record stock transaction
        $stock_out = new StockTransaction();
        $stock_out->product_id = $request->product_id;
        $stock_out->warehouse_id = $request->warehouse_id;
        $stock_out->transaction_type = 'in';
        $stock_out->quantity = $request->quantity;
        $stock_out->remarks = $request->remarks;
        $stock_out->save();

       $productStock->quantity -= $request->quantity;
        $productStock->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Stock out successfully',
            'data' => $stock_out,
        ], 201);
    }

    public function show(string $id) {}

    public function edit(string $id) {}

    public function update(Request $request, string $id) {}

    public function destroy(string $id) {}
}
