<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\UpdatePermission;
use App\Http\Requests\StorePermission;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    // function index()
    // {
    //     $permissions = Permission::all();
    //     return view('permission.index', compact('permissions'));
    // }

    // function create()
    // {
    //     return view('permission.create');
    // }

    // function store(Request $request)
    // {
    //     $request->validate([
    //         'permission_name' => 'required|string|max:255',
    //     ]);
    //     Permission::create([
    //         'name' => $request->permission_name,
    //         'guard_name' => 'web',
    //     ]);
    //     return redirect()->route('permission.index')->with('success', 'Permission created successfully.');
    // }

    // function edit($id)
    // {
    //     $permission = Permission::findOrFail($id);
    //     return view('permission.edit', compact('permission'));
    // }

    // function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'permission_name' => 'required|string|max:255',
    //     ]);

    //     $permission = Permission::findOrFail($id);
    //     $permission->update([
    //         'name' => $request->permission_name,
    //     ]);

    //     return redirect()->route('permission.index')->with('success', 'Permission updated successfully.');
    // }

    // function destroy($id)
    // {
    //     $permission = Permission::findOrFail($id);
    //     $permission->delete();

    //     return redirect()->route('permission.index')->with('success', 'Permission deleted successfully.');
    // }

    public function index(Permission $permission): View
    {
        $permissions = $permission->get(); //->paginate(5); //->orderBy('id','ASC') //->get();
        return view('permissions.index', compact(['permissions']));
    }

    public function search(Request $request)
    {
        $payload = $request->all();
        $permissions = Permission::query()
            ->when($payload, function (Builder $builder) use ($payload) {
                if (!empty($payload['search'])) {
                    $builder->where('name', $payload['search']);
                    // $builder->where('search', 'like', '%' . $payload['search'] . '%');
                }
                // if (!empty($payload['email'])) {
                //     // $builder->where('email', $payload['email']);
                //     $builder->where('email', 'like', '%' . $payload['email'] . '%');
                // }
            })
            ->paginate(10);

        // $permissions = Permission::query()
        //     ->when($request->search, function (Builder $builder) use ($request) {
        //         $builder->where('name', 'like', "%{$request->search}%");
        //     })
        //     ->paginate(5);

        return view('permissions.index', [
            'permissions' => $permissions,
            'payload' => $payload,
        ]);
    }

    public function create(): View
    {
        return view('permissions.create');
    }

    public function store(StorePermission $request)
    {
        Permission::create([
            'name' => $request->name,
            'guard_name' => 'web',
        ]);
        return redirect(route('permissions.index'))->with('success', 'Permission created successfully!!..');
    }

    public function show(Permission $permission): View
    {
        return view('permissions.show', compact(['permission']));
    }

    public function edit(Permission $permission): View
    {
        return view('permissions.edit', compact(['permission']));
    }

    public function update(UpdatePermission $request, Permission $permission)
    {
        $permission = $permission->update(['name' => $request->name]);
        if ($permission) {
            return redirect(route('permissions.index'))->with('success', 'Permissions updated successfully.!');
        } else {
            return redirect(route('permissions.index'))->with('error', 'Permissions not updated.!');
        }
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect(route('permissions.index'))->with('success', 'Permissions deleted successfully.!!');
    }
}
