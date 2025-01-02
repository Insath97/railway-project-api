<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitTypeRequest;
use App\Http\Requests\UpdateUnitTypeRequest;
use App\Http\Resources\UnitTypeResource;
use App\Models\UnitType;
use Illuminate\Http\Request;

class UnitTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Unit Type Index'])->only('index', 'show', 'getUnitType');
        $this->middleware(['permission:Unit Type Create'])->only('store');
        $this->middleware(['permission:Unit Type Update'])->only('update');
        $this->middleware(['permission:Unit Type Delete'])->only('destroy');
    }
    
    public function index()
    {
        $unitType = UnitType::where('delete_status', 1)->get();

        if ($unitType->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }
        return UnitTypeResource::collection($unitType);
    }

    public function create() {}

    public function store(StoreUnitTypeRequest $request)
    {
        $unitType = new UnitType();
        $unitType->name = $request->name;
        $unitType->abbreviation = $request->abbreviation;
        $unitType->save();

        return response()->json([
            'message' => 'Unit Type created successfully',
            'data' => $unitType,
        ], 201);
    }

    public function show(string $unitType)
    {
        $unitType = UnitType::find($unitType);

        return response()->json([
            'data' => new UnitTypeResource($unitType),
        ], 200);
    }

    public function edit(string $id) {}

    public function update(UpdateUnitTypeRequest $request, string $id)
    {
        $unitType = UnitType::findOrFail($id);
        $unitType->name = $request->name;
        $unitType->abbreviation = $request->abbreviation;
        $unitType->save();

        return response()->json([
            'message' => 'Unit Type updated successfully',
            'data' => $unitType,
        ], 201);
    }

    public function destroy(string $id)
    {
        $unitType = UnitType::findOrFail($id);
        $unitType->delete_status = 0;
        $unitType->save();

        return response()->json(
            [
                'message' => 'Unit Type deleted successfully',
            ],
            200
        );
    }

    public function getUnitType()
    {
        $unitType = UnitType::where('delete_status', 1)
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Unit Type retrieved successfully',
            'data' => $unitType
        ], 200);
    }
}
