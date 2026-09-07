<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('surprise');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate(['identity' => ['required', 'string'], 'password' => ['required', 'string']]);
        $user = User::where('email', $data['identity'])->orWhere('username', $data['identity'])->first();

        if (! $user || $user->role !== 'guest' || $user->status !== 'active' || ! Hash::check($data['password'], $user->password)) {
            return back()->withInput($request->only('identity'))->withErrors(['identity' => "Oops! This surprise isn't for you ♥️"]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route('surprise'))->with('access_granted', true);
    }

    public function showAdminLogin()
    {
        if (Auth::user()?->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('auth.admin-login');
    }

    public function adminLogin(Request $request)
    {
        $data = $request->validate(['identity' => ['required', 'string'], 'password' => ['required', 'string']]);
        $user = User::where('email', $data['identity'])->orWhere('username', $data['identity'])->first();

        if (! $user || ! $user->isAdmin() || ! Hash::check($data['password'], $user->password)) {
            return back()->withInput($request->only('identity'))->withErrors(['identity' => 'Those administrator details are not recognised.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
