<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Permission Index'])->only('index', 'show');
        $this->middleware(['permission:Permission Create'])->only('store');
        $this->middleware(['permission:Permission Update'])->only('update');
        $this->middleware(['permission:Permission Delete'])->only('destroy');
    }
    
    public function index()
    {
        $permission = Permission::orderBy('id', 'desc')->get();

        if ($permission->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return PermissionResource::collection($permission);
    }

    public function create() {}

    public function store(Request $request)
    {
        $permission = new Permission();
        $permission->group_name = $request->group_name;
        $permission->name = $request->name;
        $permission->guard_name = 'admin';
        $permission->save();

        return response()->json([
            'message' => 'Permission created successfully',
            'data' => $permission,
        ], 201);
    }

    public function show(string $permission)
    {
        $permission = Permission::find($permission);

        return response()->json([
            'data' => new PermissionResource($permission),
        ], 200);
    }

    public function edit(string $id) {}

    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);
        $permission->group_name = $request->group_name;
        $permission->name = $request->name;
        $permission->guard_name = 'admin';
        $permission->save();

        return response()->json([
            'message' => 'Permission updated successfully',
            'data' => $permission,
        ], 201);
    }

    public function destroy(string $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Permission deleted successfully',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Permission not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred while deleting the permission: ' . $e->getMessage(),
            ], 500);
        }
    }
}
