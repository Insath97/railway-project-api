<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

    public function store(Request $request)
    {
        $warehouses = Warehouse::create($request->validated());

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

    public function update(Request $request, string $id)
    {
        $warehouses = Warehouse::findOrFail($id);
        $warehouses->update($request->all());

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
