<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions',
        ]);

        Permission::create(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', 'Permission created successfully!');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update(['name' => $request->name]);
        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully!');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully!');
    }

    public function assignPermissionsForm()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $users = User::all();
        return view('permissions.assign', compact('roles', 'permissions', 'users'));
    }

    public function assignPermissions(Request $request)
    {
        $request->validate([
            'type' => 'required|in:role,user',
            'id' => 'required',
            'permissions' => 'array',
        ]);

        $entity = $request->type === 'role' ? Role::find($request->id) : User::find($request->id);

        if (!$entity) {
            return redirect()->back()->with('error', ucfirst($request->type) . ' not found.');
        }

        $entity->syncPermissions($request->permissions);
        return redirect()->back()->with('success', 'Permissions assigned successfully!');
    }
}
