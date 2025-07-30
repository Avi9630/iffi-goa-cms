<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use App\Models\CmotCategory;
use App\Models\User;

class UserController extends Controller
{
    function index()
    {
        $userRole = Auth::user()->getRoleNames()->first();
        switch ($userRole) {
            case 'SUPERADMIN':
                $users = User::paginate(10);
                $roles = Role::all();
                break;

            case 'ADMIN':
                $users = User::whereDoesntHave('roles', function ($query) {
                    $query->whereIn('name', ['superadmin']);
                })->paginate(10);
                $roles = Role::where('name', '!=', 'SUPERADMIN')->get();
                break;

            // case 'CMOT-ADMIN':
            //     $users = User::whereHas('roles', function ($query) {
            //         $query->whereIn('name', ['JURY', 'GRANDJURY', 'CMOT-ADMIN']);
            //     })->paginate(10);
            //     $roles = Role::whereIn('name', ['JURY', 'GRANDJURY', 'RECRUITER', $userRole])->get();
            //     break;

            default:
                $users = User::whereHas('roles', function ($query) {
                    $query->where('name', Auth::user()->getRoleNames());
                })->paginate(10);
                Role::where('name', Auth::user()->getRoleNames())->get();
                break;
        }
        return view('user.index', compact(['users', 'roles']));
    }

    function create()
    {
        $roles = Role::all();
        return view('user.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $payload = $request->all();
        $messages = [
            'full_name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'mobile.required' => 'The mobile field is required.',
            'mobile.digits' => 'The mobile number must be exactly 10 digits.',
            'role_id.required' => 'The role id field is required.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
        ];

        $request->validate(
            [
                'full_name' => 'required',
                'email' => ['required', 'unique:users,email'],
                'mobile' => ['required', 'unique:users,mobile', 'digits:10'],
                'role_id' => 'required',
                'password' => 'required|string|min:8|confirmed',
            ],
            $messages,
        );
        $roleName = Role::where('id', $payload['role_id'])->pluck('name');
        $data = [
            'name' => $payload['full_name'],
            'email' => $payload['email'],
            'mobile' => $payload['mobile'],
            'password' => Hash::make($payload['password']),
            'role_id' => $payload['role_id'],
        ];
        $user = User::create($data);
        if ($user) {
            $user->roles()->detach();
            if (!is_null($payload['role_id'])) {
                $user->assignRole(Role::find($payload['role_id'])->name);
            }
            return redirect()
                ->route('user.index')
                ->with('success', $roleName[0] . ' created successfully.!!');
        } else {
            return redirect()->route('user.index')->with('error', 'User not created .!!');
        }
    }

    function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('user.edit', compact(['user', 'roles']));
    }

    public function update(Request $request, $id)
    {
        $payload = $request->all();
        $messages = [
            'full_name.required' => 'The name field is required.',
            'email.required' => 'The email field is required.',
            'email.unique' => 'The email has already been taken.',
            'mobile.required' => 'The mobile field is required.',
            'mobile.digits' => 'The mobile number must be exactly 10 digits.',
            'role_id.required' => 'The role id field is required.',
        ];

        $request->validate(
            [
                'full_name' => 'required',
                'email' => ['required', "unique:users,email,$id"],
                'mobile' => ['required', "unique:users,mobile,$id", 'digits:10'],
                'role_id' => 'required',
            ],
            $messages,
        );

        $user = User::findOrFail($id);
        $data = [
            'name' => $payload['full_name'],
            'email' => $payload['email'],
            'mobile' => $payload['mobile'],
            'role_id' => $payload['role_id'],
        ];
        if ($user->update($data)) {
            $user->roles()->detach();
            if (!is_null($payload['role_id'])) {
                $user->assignRole(Role::find($payload['role_id'])->name);
            }
            return redirect()->route('user.index')->with('success', 'User updated successfully.!!');
        } else {
            return redirect()->route('user.index')->with('error', 'User not updated.!!');
        }
    }

    function destroy($id)
    {
        if (Auth::user()->id == $id) {
            return redirect()->route('user.index')->with('danger', 'You cannot delete your own account.!!');
        }
        $user = User::findOrFail($id);
        if ($user->delete()) {
            return redirect()->route('user.index')->with('success', 'User deleted successfully.!!');
        } else {
            return redirect()->route('user.index')->with('error', 'User not deleted.!!');
        }
    }
}
