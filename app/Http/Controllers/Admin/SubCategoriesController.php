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
    public function index()
    {
        $subCategory = SubCategory::where('delete_status', 1)->get();

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
        $subCategory->save();

        return response()->json([
            'message' => 'Sub Category created successfully',
            'data' => $subCategory,
        ], 200);
    }

    public function show(string $Subcategory)
    {
        $subCategory = SubCategory::find($Subcategory);

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

    public function getSubCategory(Request $request)
    {
        $subCategory = SubCategory::where(['main_category_id' => 1, 'delete_status' => 1])
            ->select('id', 'code', 'name')
            ->orderBy('name', 'asc')
            ->get();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Category retrieved successfully',
            'data' => $subCategory
        ], 200);
    }
}
