<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    function index()
    {
        $permissions = Permission::all();
        return view('permission.index', compact('permissions'));
    }

    function create()
    {
        return view('permission.create');
    }

    function store(Request $request)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255',
        ]);
        Permission::create([
            'name' => $request->permission_name,
            'guard_name' => 'web',
        ]);
        return redirect()->route('permission.index')->with('success', 'Permission created successfully.');
    }

    function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permission.edit', compact('permission'));
    }

    function update(Request $request, $id)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255',
        ]);

        $permission = Permission::findOrFail($id);
        $permission->update([
            'name' => $request->permission_name,
        ]);

        return redirect()->route('permission.index')->with('success', 'Permission updated successfully.');
    }

    function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $permission->delete();

        return redirect()->route('permission.index')->with('success', 'Permission deleted successfully.');
    }
}
