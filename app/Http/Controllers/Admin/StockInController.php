<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use Illuminate\Http\Request;

class StockInController extends Controller
{
    public function index() {}

    public function create() {}

    public function store(Request $request)
    {
        $stock_in = new StockTransaction();
        $stock_in->product_id = $request->product_id;
        $stock_in->warehouse_id = $request->warehouse_id;
        $stock_in->transaction_type = 'in';
        $stock_in->quantity = $request->quantity;
        $stock_in->remarks = $request->remarks;
        $stock_in->save();

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
