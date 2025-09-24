<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'phone_number' => ['required', 'string', 'max:16'],
            'date_of_birth' => ['required', 'date'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'date_of_birth' => $request->date_of_birth,
                'password' => Hash::make($request->password),
            ]);

            Auth::login($user);
            session()->flash('success', 'Successfully registered! Please login.');

            return redirect()->route('login-form');
        } catch (\Exception $e) {
            session()->flash('error', 'Terjadi kesalahan saat registrasi. ' . $e->getMessage());
        }

        return redirect()->route('register-form');
    }
}
