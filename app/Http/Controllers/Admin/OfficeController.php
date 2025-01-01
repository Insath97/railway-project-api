<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOfficeRequest;
use App\Http\Requests\UpdateOfficeRequest;
use App\Http\Resources\OfficeResource;
use App\Models\Office;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Stringable;

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
        $offices->update($request->validated());

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

    public function getOffices(): JsonResponse
    {
        $offices = Office::where('delete_status', 1)
            ->select('id', 'code', 'office_name')
            ->orderBy('office_name', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Offices retrieved successfully',
            'data' => $offices
        ], 200);
    }

    public function getDivisions(): JsonResponse
    {
        // Path to the JSON file
        $filePath = resource_path('json/divisions.json');

        // Check if the file exists
        if (!file_exists($filePath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        // Read the JSON file
        $json = file_get_contents($filePath);
        $data = json_decode($json, true);

        // Check for JSON decoding errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'message' => 'Error decoding JSON: ' . json_last_error_msg()
            ], 500);
        }

        // Return the divisions
        return response()->json([
            'success' => true,
            'data' => $data['divisions'] ?? [],
        ], 200);
    }

    public function getOfficesDivision(string $division_name): JsonResponse
    {
        $offices = Office::where('division', $division_name)
            ->where('delete_status', 1)
            ->select('id', 'code', 'office_name')
            ->orderBy('office_name', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Offices retrieved successfully',
            'data' => $offices
        ], 200);
    }
}
