<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\RolesResource;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:Role Index'])->only('index', 'show');
        $this->middleware(['permission:Role Create'])->only('store');
        $this->middleware(['permission:Role Update'])->only('update');
        $this->middleware(['permission:Role Delete'])->only('destroy');
    }
    public function index()
    {
        $role = Role::all();

        if ($role->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return RolesResource::collection($role);
    }

    public function create() {}

    public function store(Request $request)
    {
        $role = Role::create(['guard_name' => 'admin', 'name' => $request->name]);
        $role->syncPermissions($request->permissions);

        $roleWithPermissions = Role::with('permissions')->findOrFail($role->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Role created and permissions assigned successfully',
            'data' => [
                'role' => $roleWithPermissions,
                'permissions' => $roleWithPermissions->permissions->pluck('name'),
            ],
        ], 201);
    }

    public function show(string $id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all()->groupBy('group_name');
        $role_permissions = $role->permissions->pluck('name')->toArray();

        return response()->json([
            'status' => 'success',
            'data' => [
                'role' => $role,
                'permissions' => $permissions,
                'role_permissions' => $role_permissions
            ]
        ], 200);
    }

    public function edit(string $id) {}

    public function update(Request $request, string $id)
    {
        $role = Role::findOrFail($id);
        $role->update(['guard_name' => 'admin', 'name' => $request->name]);
        $role->syncPermissions($request->permissions);

        $roleWithPermissions = Role::with('permissions')->findOrFail($role->id);

        return response()->json([
            'status' => 'success',
            'message' => 'Role & permissions updated successfully',
            'data' => [
                'role' => $role,
                'permissions' => $roleWithPermissions->permissions->pluck('name'),
            ],
        ], 200);
    }

    public function destroy($id)
    {
        try {

            $role = Role::findOrFail($id);

            if ($role->name === 'Super Admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete this role, it is the default Super Admin role.'
                ], 400);
            }

            $role->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Role & permissions deleted successfully'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }

    public function getRolesPermissions()
    {
        $roles = Role::with('permissions')->get();

        $data = $roles->map(function ($role) {
            return [
                'role' => $role->name,
                'permissions' => $role->permissions->pluck('name'),
            ];
        });

        return response()->json([
            'message' => 'All roles and permissions fetched successfully',
            'data' => $data,
        ], 200);
    }
}
