<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        // Validasi input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Coba autentikasi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            session()->put('id_users', $user->id);
            session()->put('name', $user->name);
            session()->put('email', $user->email);

            return redirect()->intended('/');
        }


        // Jika gagal
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }


    // Logout user
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
