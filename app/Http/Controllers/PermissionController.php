<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    public function index(Permission $permission): View
    {
        $permissions = $permission->get();
        return view('permission.index', compact(['permissions']));
    }

    public function create(): View
    {
        return view('permission.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255',
        ]);
        Permission::create([
            'name' => $request->permission_name,
            'guard_name' => 'web',
        ]);
        return redirect(route('permission.index'))->with('success', 'Permission created successfully!!..');
    }

    public function edit(Permission $permission): View
    {
        return view('permission.edit', compact(['permission']));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'permission_name' => 'required|string|max:255',
        ]);
        $permission = $permission->update(['name' => $request->permission_name]);
        if ($permission) {
            return redirect(route('permission.index'))->with('success', 'Permissions updated successfully.!');
        } else {
            return redirect(route('permission.index'))->with('error', 'Permissions not updated.!');
        }
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect(route('permission.index'))->with('success', 'Permissions deleted successfully.!!');
    }
}
