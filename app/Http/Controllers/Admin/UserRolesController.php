<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRoleStoreRequest;
use App\Http\Requests\UserRoleUpdateRequest;
use App\Http\Resources\UserRolesResource;
use App\Models\Aadmin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserRoleMail;

class UserRolesController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:User Role Index'])->only('index', 'show');
        $this->middleware(['permission:User Role Create'])->only('store');
        $this->middleware(['permission:User Role Update'])->only('update');
        $this->middleware(['permission:User Role Delete'])->only('destroy');
    }
    
    public function index()
    {
        $users = Aadmin::with('office', 'warehouse')->get();

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No Data Found'], 200);
        }

        return UserRolesResource::collection($users);
    }

    public function create() {}

    public function store(UserRoleStoreRequest $request)
    {
        $users = new Aadmin();
        $users->office_id = $request->office_id;
        $users->warehouse_id = $request->warehouse_id;
        $users->name = $request->name;
        $users->email = $request->email;
        $users->password = bcrypt($request->password);
        $users->save();

        $users->assignRole($request->role);

        Mail::to($request->email)->send(new UserRoleMail($request->email, $request->password, $request->role));

        return response()->json([
            'message' => 'User created successfully',
            'data' => $users,
        ], 201);
    }

    public function show(string $users)
    {
        $users = Aadmin::with('office', 'warehouse')->find($users);

        return response()->json([
            'data' => new UserRolesResource($users),
        ], 200);
    }

    public function edit(string $id) {}

    public function update(UserRoleUpdateRequest $request, string $id)
    {

        if ($request->has('password') && !empty($request->password)) {
            $request->validate([
                'password' => ['confirmed', 'min:8']
            ]);
        }

        $users = Aadmin::findOrFail($id);
        $users->office_id = $request->office_id;
        $users->warehouse_id = $request->warehouse_id;
        $users->name = $request->name;
        $users->email = $request->email;

        if ($request->has('password') && !empty($request->password)) {
            $users->password = bcrypt($request->password);
        }

        $users->save();

        $users->syncRoles($request->role);

        return response()->json([
            'message' => 'User update successfully',
            'data' => $users,
        ], 201);
    }

    public function destroy(string $id)
    {
        try {
            $user = Aadmin::findOrFail($id);


            if ($user->getRoleNames()->first() === 'Super Admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Can\'t delete this user. This is the default role.'
                ], 400);
            }

            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ], 200);
        } catch (\Throwable $th) {

            return response()->json([
                'status' => 'error',
                'message' => 'Error: ' . $th->getMessage()
            ], 500);
        }
    }
}
