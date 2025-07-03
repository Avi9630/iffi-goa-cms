<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    function registerView()
    {
        return view('auth.register');
    }

    function register(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string'],
            'email'     => ['required', 'email', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $user = User::create($request->all());
        if ($user) {
            return redirect('register')->with('success', 'User Registered Successfully!!');
        } else {
            return redirect('register')->withError('error', 'Something Went Wrong!!');
        }
    }

    function loginView()
    {
        return view('auth.login');
    }

    function login(Request $request)
    {
        $request->validate([
            'email'     =>  ['required', 'email'],
            'password'  =>  'required|string|min:8',
        ]);
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->with(['danger' => 'Invalid credentials.']);
        }
        Auth::login($user);
        return redirect()->intended('/');
    }

    function logut()
    {
        // Session::flush();
        Auth::logout();
        return redirect('/');
    }
}
