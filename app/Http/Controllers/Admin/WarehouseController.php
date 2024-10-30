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
        $warehouses = Warehouse::where('delete_status', 1)->get();

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
        $warehouses = Warehouse::find($warehouse);
        return response()->json([
            'data' => new WarehouseResource($warehouses),
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
}
