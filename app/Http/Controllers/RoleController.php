<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    function index()
    {
        $roles = Role::all();
        return view('role.index', compact('roles'));
    }

    function create()
    {
        return view('role.create');
    }

    function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
        ]);

        Role::create([
            'name' => $request->role_name,
            'guard_name' => 'web',
        ]);

        return redirect()->route('role.index')->with('success', 'Role created successfully.');
    }

    function edit(Role $role)
    {
        return view('role.edit', compact('role'));
    }

    function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
        ]);

        $role->update([
            'name' => $request->role_name,
        ]);

        return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    }

    function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
    }
}
