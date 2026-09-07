<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function edit()
    {
        return view('admin.user', ['guest' => User::where('role', 'guest')->first()]);
    }

    public function update(Request $request)
    {
        $guest = User::where('role', 'guest')->first();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'string', 'min:3', 'max:50', Rule::unique('users', 'username')->ignore($guest?->id)],
            'email' => ['required', 'email', 'max:150', Rule::unique('users', 'email')->ignore($guest?->id)],
            'password' => [$guest ? 'nullable' : 'required', 'string', 'min:8', 'max:100'],
            'status' => ['required', 'in:active,paused'],
        ]);

        $guest ??= new User(['role' => 'guest']);
        $guest->name = $data['name'];
        $guest->username = $data['username'];
        $guest->email = $data['email'];
        $guest->role = 'guest';
        $guest->status = $data['status'];
        if (! empty($data['password'])) {
            $guest->password = Hash::make($data['password']);
        }
        $guest->save();

        return back()->with('success', 'Frontend user access saved successfully.');
    }
}
