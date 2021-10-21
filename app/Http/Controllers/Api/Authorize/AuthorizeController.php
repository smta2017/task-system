<?php

namespace App\Http\Controllers\Api\Authorize;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthorizeController extends Controller
{
    public function roles()
    {
        $roles = Role::all();
        return response()->json($roles, 200);
    }

    public function permissions()
    {
        $permissions = Permission::all();
        return response()->json($permissions, 200);
    }

    public function createPermission($name)
    {
        $permission = Permission::create(['name'=>$name]);
        return response()->json($permission, 200);
    }

    public function createRoles($name)
    {
        $role = Role::create(['name'=>$name]);
        return response()->json($role, 200);
    }

    public function assignRoleToPermission($permission,$role_id)
    {
        $role = Role::find($role_id);
        $permission = Permission::where('name',$permission)->first();
        $permission->assignRole($role);
        return response()->json($role->permission, 200);
    }

    public function rolePermission($role_id)
    {
        $role = Role::find($role_id);
        $rolePermission = $role->permission;
        return response()->json($rolePermission, 200);
    }

    public function assignRole(Request $request)
    {
        $role = Role::where(['name'=>name]);
        $user = User::find($request->id);
        $user->assignRole($role);
        return response()->json($user->getAllPermissios(), 200);

        // $user = User::find($request->id);
        // $user->assignRole($request->role);
        // return response()->json($user->getAllPermissios(), 200);
    }

    public function userPermission($user_id)
    {
        $user = User::find($user_id);
        return response()->json($user->getAllPermissios(), 200);
    }

    public function userRole($user_id)
    {
        $user = User::find($user_id);
        return response()->json($user->getAllRoles(), 200);
    }

    public function revoke($role_id,$per_id)
    {
        $role = Role::find($role_id);
        $permission = Permission::find($per_id);
        $role->revokePermissionTo($permission);
    }
}
