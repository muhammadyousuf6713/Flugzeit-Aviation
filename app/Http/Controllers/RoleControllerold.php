<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::all(); // Get all roles
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create'); // View for creating a role
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name|max:255',
        ]);

        Role::create(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role created successfully!');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id); // Find role by ID
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id . '|max:255',
        ]);

        $role->update(['name' => $request->name]);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully!');
    }
}
