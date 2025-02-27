<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSubCategoryRequest;
use App\Http\Requests\UpdateSubCategoryRequest;
use App\Http\Resources\SubCategoriesResource;
use App\Models\SubCategory;
use Illuminate\Http\Request;

class SubCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Sub Category Index'])->only('index', 'show', 'getSubCategory');
        $this->middleware(['permission:Sub Category Create'])->only('store');
        $this->middleware(['permission:Sub Category Update'])->only('update');
        $this->middleware(['permission:Sub Category Delete'])->only('destroy');
    }
    
    public function index()
    {
        $subCategory = SubCategory::with('mainCategory')->where('delete_status', 1)->get();

        if ($subCategory->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return SubCategoriesResource::collection($subCategory);
    }

    public function create() {}

    public function store(StoreSubCategoryRequest $request)
    {
        $subCategory = new SubCategory();
        $subCategory->main_category_id = $request->main_category_id;
        $subCategory->code = $request->code;
        $subCategory->name = $request->name;
        $subCategory->description = $request->description;
        $subCategory->save();

        return response()->json([
            'message' => 'Sub Category created successfully',
            'data' => $subCategory,
        ], 200);
    }

    public function show(string $Subcategory)
    {
        $subCategory = SubCategory::with('mainCategory')->find($Subcategory);

        return response()->json([
            'data' => new SubCategoriesResource($subCategory),
        ], 200);
    }

    public function edit(string $id) {}

    public function update(UpdateSubCategoryRequest $request, string $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->main_category_id = $request->main_category_id;
        $subCategory->code = $request->code;
        $subCategory->name = $request->name;
        $subCategory->description = $request->description;
        $subCategory->save();

        return response()->json([
            'message' => 'Sub Category updated successfully',
            'data' => $subCategory,
        ], 200);
    }

    public function destroy(string $id)
    {
        $subCategory = SubCategory::findOrFail($id);
        $subCategory->delete_status = 0;
        $subCategory->save();

        return response()->json(
            [
                'message' => 'Sub Category deleted successfully',
            ],
            200
        );
    }

    public function getSubCategory(string $id)
    {
        $subCategory = SubCategory::where([
            'main_category_id' => $id,
            'delete_status' => 1
        ])
            ->select('id', 'code', 'name')
            ->orderBy('name', 'asc')
            ->get();

        if ($subCategory->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Sub Categories found for the specified Main Category',
                'data' => []
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Categories retrieved successfully',
            'data' => $subCategory
        ], 200);
    }
}
