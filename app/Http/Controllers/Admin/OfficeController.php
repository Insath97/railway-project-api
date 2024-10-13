<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfficeRequest;
use App\Http\Requests\UpdateOfficeRequest;
use App\Http\Resources\OfficeResource;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $offices = Office::where('delete_status', 1)->get();

        if ($offices->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return OfficeResource::collection($offices);
    }

    public function create() {}

    public function store(StoreOfficeRequest $request)
    {
        $office = Office::create($request->validated());

        return response()->json([
            'message' => 'Office created successfully',
            'data' => $office,
        ], 201);
    }

    public function show(string $office)
    {
        $office = Office::find($office);

        return response()->json([
            'data' => new OfficeResource($office),
        ], 200);
    }

    public function edit(string $id) {}

    public function update(UpdateOfficeRequest $request, string $id)
    {
        $offices = Office::findOrFail($id);
        $offices->update($request->all());

        return response()->json([
            'message' => 'Office updated successfully',
            'data' => $offices,
        ], 201);
    }

    public function destroy(string $id)
    {
        $offices = Office::findOrFail($id);
        $offices->delete_status = 0;
        $offices->save();

        return response()->json(
            [
                'message' => 'Office deleted successfully',
            ],
            200
        );
    }
}
