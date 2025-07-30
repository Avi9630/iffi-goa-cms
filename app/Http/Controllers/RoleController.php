<?php

namespace App\Http\Controllers;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\UpdateRoleRequest;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;
use DB;

class RoleController extends Controller
{
    // function index()
    // {
    //     $roles = Role::all();
    //     return view('role.index', compact('roles'));
    // }

    // function create()
    // {
    //     return view('role.create');
    // }

    // function store(Request $request)
    // {
    //     $request->validate([
    //         'role_name' => 'required|string|max:255',
    //     ]);

    //     Role::create([
    //         'name' => $request->role_name,
    //         'guard_name' => 'web',
    //     ]);

    //     return redirect()->route('role.index')->with('success', 'Role created successfully.');
    // }

    // function edit(Role $role)
    // {
    //     return view('role.edit', compact('role'));
    // }

    // function update(Request $request, Role $role)
    // {
    //     $request->validate([
    //         'role_name' => 'required|string|max:255',
    //     ]);

    //     $role->update([
    //         'name' => $request->role_name,
    //     ]);

    //     return redirect()->route('role.index')->with('success', 'Role updated successfully.');
    // }

    // function destroy(Role $role)
    // {
    //     $role->delete();
    //     return redirect()->route('role.index')->with('success', 'Role deleted successfully.');
    // }

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
        return redirect()->route('roles.index')->with('success', 'New role is added successfully.');
    }

    public function edit(Role $role): View
    {
        if ($role->name == 'SUPERADMIN') {
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE EDITED');
        }

        $rolePermissions = DB::table('role_has_permissions')->where('role_id', $role->id)->pluck('permission_id')->all();

        return view('roles.edit', [
            'role' => $role,
            'permissions' => Permission::get(),
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        $input = $request->only('name');
        $role->update($input);
        $permissions = Permission::whereIn('id', $request->permissions)
            ->get(['name'])
            ->toArray();
        $role->syncPermissions($permissions);
        return redirect()->route('roles.index')->with('success', 'Role is updated successfully.');
    }

    public function show(Role $role): View
    {
        $rolePermissions = Permission::join('role_has_permissions', 'permission_id', '=', 'id')->where('role_id', $role->id)->select('name')->get();
        return view('roles.show', ['role' => $role, 'rolePermissions' => $rolePermissions]);
    }

    public function destroy(Role $role)
    {
        if ($role->name == 'SUPERADMIN') {
            abort(403, 'SUPER ADMIN ROLE CAN NOT BE DELETED');
        }

        if (auth()->user()->hasRole($role->name)) {
            abort(403, 'CAN NOT DELETE SELF ASSIGNED ROLE');
        }

        if (Auth::user()->usertype == 2) {
            if ($role->name == 'ADMIN') {
                abort(403, 'CAN NOT DELETE UPPER ROLE');
            }
        }

        if (Auth::user()->usertype == 3) {
            if ($role->name == 'ADMIN' || $role->name == 'CORPORATE') {
                abort(403, 'CAN NOT DELETE UPPER ROLE');
            }
        }

        if (Auth::user()->usertype == 4) {
            if ($role->name == 'ADMIN' || $role->name == 'CORPORATE' || $role->name == 'USER') {
                abort(403, 'CAN NOT DELETE UPPER ROLE');
            }
        }
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role is deleted successfully.');
    }
}
