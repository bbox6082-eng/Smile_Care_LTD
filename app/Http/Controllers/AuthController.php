<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $usernameOrEmail = $request->username;

        $ok = Auth::attempt([
            'username' => $usernameOrEmail,
            'password' => $request->password,
            'status' => 'active',
        ]);

        if (!$ok && filter_var($usernameOrEmail, FILTER_VALIDATE_EMAIL)) {
            $ok = Auth::attempt([
                'email' => $usernameOrEmail,
                'password' => $request->password,
                'status' => 'active',
            ]);
        }

        if ($ok) {
            $request->session()->regenerate();
            
            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.dashboard');
            } else {
                return redirect()->route('lab.dashboard');
            }
        }

        return back()->withErrors([
            'username' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }
}
