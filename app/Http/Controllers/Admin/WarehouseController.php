<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWarehouseRequest;
use App\Http\Requests\UpdateWarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{

    public function index()
    {
        $warehouses = Warehouse::with('office')->where('delete_status', 1)->get();

        if ($warehouses->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }
        return WarehouseResource::collection($warehouses);
    }

    public function create() {}

    public function store(StoreWarehouseRequest $request)
    {
        $warehouses = new Warehouse();
        $warehouses->office_id = $request->office_id;
        $warehouses->warehouse_code = $request->warehouse_code;
        $warehouses->warehouse_name = $request->warehouse_name;
        $warehouses->address = $request->address;
        $warehouses->phone_number = $request->phone_number;
        $warehouses->email = $request->email;
        $warehouses->save();

        return response()->json([
            'message' => 'Warehouse created successfully',
            'data' => $warehouses,
        ], 201);
    }

    public function show(string $warehouse)
    {
        $warehouse = Warehouse::with('office')->where('delete_status', 1)->find($warehouse);

        if (!$warehouse) {
            return response()->json([
                'status' => 'error',
                'message' => 'Warehouse not found',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => new WarehouseResource($warehouse),
        ], 200);
    }


    public function edit(string $id) {}

    public function update(UpdateWarehouseRequest $request, string $id)
    {
        $warehouses = Warehouse::findOrFail($id);
        $warehouses->office_id = $request->office_id;
        $warehouses->warehouse_code = $request->warehouse_code;
        $warehouses->warehouse_name = $request->warehouse_name;
        $warehouses->address = $request->address;
        $warehouses->phone_number = $request->phone_number;
        $warehouses->email = $request->email;
        $warehouses->save();

        return response()->json([
            'message' => 'Warehouse updated successfully',
            'data' => $warehouses,
        ], 201);
    }

    public function destroy(string $id)
    {
        $warehouses = Warehouse::findOrFail($id);
        $warehouses->delete_status = 0;
        $warehouses->save();

        return response()->json(
            [
                'message' => 'Warehouse deleted successfully',
            ],
            200
        );
    }

    public function getWarehouse(string $id)
    {
        $warehouse = Warehouse::where(['office_id' => $id, 'delete_status' => 1])
            ->select('id', 'warehouse_code', 'warehouse_name')
            ->orderBy('warehouse_name', 'asc')
            ->get();

        if ($warehouse->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No warehouse data found for the specified Office',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Warehouse retrieved successfully',
            'data' => $warehouse
        ], 200);
    }
}
