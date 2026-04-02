<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        $roles       = Role::with('permissions')->get();
        return view('backend.permissions.index', compact('permissions', 'roles'));
    }

    // Permission CRUD
    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name]);

        return back()->with('success', 'Permission created!');
    }

    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
        return back()->with('success', 'Permission deleted!');
    }

    // Role CRUD
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        Role::create(['name' => $request->name]);

        return back()->with('success', 'Role created!');
    }

    public function destroyRole(Role $role)
    {
        if (in_array($role->name, ['admin', 'creator', 'reader'])) {
            return back()->with('error', 'Cannot delete default roles!');
        }

        $role->delete();
        return back()->with('success', 'Role deleted!');
    }

    // Assign permissions to role
    public function assignToRole(Request $request)
    {
        $request->validate([
            'role'        => 'required|exists:roles,name',
            'permissions' => 'array',
        ]);

        $role = Role::findByName($request->role);
        $role->syncPermissions($request->permissions ?? []);

        return back()->with('success', 'Permissions updated for ' . ucfirst($request->role) . ' role!');
    }
}
