<?php

namespace App\Http\Controllers;

use App\AccountsPermissions;
use App\ControlAccount;
use App\MainAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\role_permission;
use App\SubControlAccount;
use App\TransactionAccount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    // Create New Permission
    public function index()
    {
        $permissions = Permission::with('parent')->get(); // Assuming a parent-child relationship
        return view('permission.per_create.index', compact('permissions'));
    }

    // Show create form
    public function create()
    {
        $modules = Permission::where('parent_id', 0)->get(); // Fetch main modules
        return view('permission.per_create.create', compact('modules'));
    }

    public function getSubModules(Request $request)
    {
        $parentId = $request->input('parent_id');
        $subModules = Permission::where('parent_id', $parentId)->get();

        return response()->json([
            'subModules' => $subModules,
        ]);
    }

    // Store new permission
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:permissions,id',
            'sub_module_id' => 'nullable|exists:permissions,id',
        ]);

        // Determine the parent ID
        $parentId = $request->sub_module_id ?? $request->parent_id ?? 0; // Default to 0 for top-level modules

        // Create the permission
        Permission::create([
            'name' => $request->name,
            'parent_id' => $parentId, // Store the appropriate parent
            'guard_name' => 'web', // Assuming you use 'web' as the guard name
        ]);

        return redirect()->route('permission.index')->with('success', 'Permission created successfully.');
    }


    // Show edit form
    public function edit(Permission $permission)
    {
        $modules = Permission::where('parent_id', 0)->get();
        return view('permission.per_create.edit', compact('permission', 'modules'));
    }

    // Update permission
    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name,' . $permission->id,
            'parent_id' => 'nullable|integer|exists:permissions,id',
        ]);

        $permission->update([
            'name' => $request->name,
            'parent_id' => $request->parent_id ?? 0,
        ]);

        return redirect()->route('permission.index')->with('success', 'Permission updated successfully.');
    }

    // Delete permission
    public function destroy(Permission $permission)
    {
        if ($permission->children()->exists()) {
            return redirect()->route('permission.index')->with('error', 'Cannot delete a module with sub-modules.');
        }

        $permission->delete();
        return redirect()->route('permission.index')->with('success', 'Permission deleted successfully.');
    }










    // Assin Permissions to Roles and users

    public function permission_index(Request $request)
    {
        $roles = Role::all();
        $role_id = $request->id ?? 0;

        $permissions = Permission::all();


        $assigned_permissions = $role_id ? Role::find($role_id)->permissions->pluck('id')->toArray() : [];

        return view('permission.index', compact('roles', 'role_id', 'permissions', 'assigned_permissions'));
    }


    // public function assignPermissions(Request $request, $role_id)
    // {
    //     // Validate the request
    //     $validated = $request->validate([
    //         'permissions' => 'array',
    //         'permission.*' => 'integer|exists:permissions,id',
    //     ]);

    //     // Find the role
    //     $role = Role::find($role_id);
    //     if (!$role) {
    //         return redirect()->back()->with('alert', 'Role not found')->with('alert-class', 'danger');
    //     }

    //     // Filter permissions for the 'web' guard
    //     $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])
    //         ->where('guard_name', 'web')
    //         ->pluck('name') // Get the names of the permissions
    //         ->toArray();

    //     // Sync permissions to the role (adds and removes accordingly)
    //     $role->syncPermissions($permissions);

    //     // Sync permissions for the authenticated user
    //     $user = auth()->user();

    //     // Get all permissions assigned to the user
    //     $userPermissions = $user->getPermissionNames()->toArray();

    //     // Add new permissions to the user
    //     foreach ($permissions as $permission) {
    //         if (!in_array($permission, $userPermissions)) {
    //             $user->givePermissionTo($permission);
    //         }
    //     }

    //     // Remove permissions that are unchecked
    //     foreach ($userPermissions as $permission) {
    //         if (!in_array($permission, $permissions)) {
    //             $user->revokePermissionTo($permission);
    //         }
    //     }

    //     // Set success response
    //     return redirect()->back()->with('alert', 'Permissions updated successfully')->with('alert-class', 'success');
    // }

    public function assignPermissions(Request $request, $role_id)
    {
        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        $role = Role::find($role_id);
        if (!$role) {
            return redirect()->back()->with('alert', 'Role not found')->with('alert-class', 'danger');
        }

        // Retrieve permission names
        $permissions = Permission::whereIn('id', $validated['permissions'] ?? [])
            ->where('guard_name', 'web')
            ->pluck('name')
            ->toArray();

        // Sync permissions with the role
        $role->syncPermissions($permissions);

        return redirect()->back()->with('alert', 'Permissions updated successfully')->with('alert-class', 'success');
    }
}
