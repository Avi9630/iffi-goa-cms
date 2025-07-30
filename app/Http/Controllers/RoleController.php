<?php

namespace App\Http\Controllers;

use Spatie\Permission\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use DB;

class RoleController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:create-role|edit-role|list-role|delete-role', ['only' => ['index','show']]);
        // $this->middleware('permission:create-role', ['only' => ['create','store']]);
        // $this->middleware('permission:edit-role',   ['only' => ['edit','update']]);
        // $this->middleware('permission:delete-role', ['only' => ['destroy']]);
        $this->user = Auth::user();
    }

    public function index(Role $role): View
    {
        // $roleName = Role::select('name')->where('id', $this->user->role_id)->first();
        // dd(Auth::user()->usertype);
        // if (Auth::user()->usertype == 0) {
        //     $roles  =   $role->where('id', '!=', Auth::id())->orderBy('id', 'ASC')->get();
        // } else if (Auth::user()->usertype == 1) {
        //     $roles  =   $role->whereNotIn('name', ['SUPERADMIN'])->orderBy('id', 'ASC')->get();
        // } else if (Auth::user()->usertype == 2) {
        //     $roles  =   $role->whereNotIn('name', ['SUPERADMIN', 'ADMIN'])->orderBy('id', 'ASC')->get();
        // } else if (Auth::user()->usertype == 3) {
        //     $roles  =   $role->whereNotIn('name', ['SUPERADMIN', 'ADMIN', 'CORPORATE'])->orderBy('id', 'ASC')->get();
        // } else {
        //     $roles  =   $role->where('usertype', 4)->orderBy('id', 'ASC')->get();
        // }
        // $roles  =   $role->orderBy('id','ASC')->get();
        $roles = Role::orderBy('id', 'ASC')->get(); //->paginate(5);
        return view('role.index', compact(['roles']));
    }

    public function create(): View
    {
        return view('role.create', [
            'permissions' => Permission::get(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'permissions' => 'required',
        ]);

        $role = Role::create(['name' => $request->role_name]);
        $permissions = Permission::whereIn('id', $request->permissions)
            ->get(['name'])
            ->toArray();
        $role->syncPermissions($permissions);
        return redirect()->route('role.index')->with('success', 'New role is added successfully.');
    }

    public function edit(Role $role): View
    {
        if ($role->name == 'SUPERADMIN') {
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE EDITED');
        }
        $rolePermissions = DB::table('role_has_permissions')->where('role_id', $role->id)->pluck('permission_id')->all();
        return view('role.edit', [
            'role' => $role,
            'permissions' => Permission::get(),
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name' => 'required|string|max:250|unique:roles,name,' . $role->id,
            'permissions' => 'required',
        ]);
        $input = $request->only('role_name');
        $role->update($input);
        $permissions = Permission::whereIn('id', $request->permissions)
            ->get(['name'])
            ->toArray();
        $role->syncPermissions($permissions);
        return redirect()->route('role.index')->with('success', 'Role is updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->name == 'SUPERADMIN') {
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE DELETED');
        }

        if (Auth::user()->hasRole($role->name)) {
            abort(403, 'CAN NOT DELETE SELF ASSIGNED ROLE');
        }

        if (Auth::user()->usertype == 2) {
            if ($role->name == 'ADMIN') {
                abort(403, 'CAN NOT DELETE UPPER ROLE');
            }
        }
        $role->delete();
        return redirect()->route('role.index')->with('success', 'Role is deleted successfully.');
    }
}
