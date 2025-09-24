<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if the authenticated user's email is "pertare.asets@gmail.com"
            if (Auth::user()->email === 'pertare.asets@gmail.com') {
                return redirect()->route('performance-calc')->with('successLogin', 'Login berhasil! Selamat datang.');
            }
            return redirect()->route('home')->with('successLogin', 'Login berhasil! Selamat datang.');
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('successLogout', 'Login berhasil! Selamat datang.');
    }
}
