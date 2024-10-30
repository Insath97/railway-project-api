<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMainCategoryRequest;
use App\Http\Requests\UpdateMainCategoryRequest;
use App\Http\Resources\MainCategoriesResource;
use App\Models\MainCategory;
use Illuminate\Http\Request;

class MainCategoriesController extends Controller
{
    public function index()
    {
        $MainCategories = MainCategory::where('delete_status', 1)->get();

        if ($MainCategories->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return MainCategoriesResource::collection($MainCategories);
    }

    public function create() {}

    public function store(StoreMainCategoryRequest $request)
    {
        $MainCategories = MainCategory::create($request->validated());

        return response()->json([
            'message' => 'Main Category created successfully',
            'data' => $MainCategories,
        ], 200);
    }

    public function show(string $MainCategory)
    {
        $MainCategory = MainCategory::find($MainCategory);

        return response()->json([
            'data' => new MainCategoriesResource($MainCategory),
        ], 200);
    }

    public function edit(string $id) {}

    public function update(UpdateMainCategoryRequest $request, string $id)
    {
        $MainCategory = MainCategory::findOrFail($id);
        $MainCategory->update($request->validated());

        return response()->json([
            'message' => 'Main Category updated successfully',
            'data' => $MainCategory,
        ], 201);
    }

    public function destroy(string $id)
    {
        $MainCategory = MainCategory::findOrFail($id);
        $MainCategory->delete_status = 0;
        $MainCategory->save();

        return response()->json(
            [
                'message' => 'Main Category deleted successfully',
            ],
            200
        );
    }
}
