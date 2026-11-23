<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin() { return view('login'); }

    public function login(Request $request) {
        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/');
        }
        return back()->with('error', 'Email atau Password salah!');
    }

    public function showRegister() { return view('register'); }

    public function register(Request $request) {
        // 1. Menjalankan aturan validasi ketat
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email', // Cek format email & pastikan belum terdaftar
            'password' => [
                'required',
                'min:8',             // Minimal 8 karakter
                'regex:/[a-z]/',      // Harus ada huruf kecil
                'regex:/[A-Z]/',      // Harus ada huruf besar
                'regex:/[0-9]/',      // Harus ada angka
                'regex:/[@$!%*#?&]/', // Harus ada simbol/karakter spesial
            ],
        ], [
            // Pesan error kustom dalam Bahasa Indonesia
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid (harus pakai @).',
            'email.unique' => 'Email ini sudah terdaftar, silakan gunakan email lain.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 8 karakter.',
            'password.regex' => 'Password harus kombinasi huruf besar, kecil, angka, dan simbol (@$!%*#?&).',
        ]);

        // 2. Jika lolos validasi, simpan data ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Redirect ke login dengan pesan sukses
        return redirect('/login')->with('success', 'Berhasil daftar, silakan login!');
    }

    public function logout() {
        Auth::logout();
        return redirect('/login');
    }
}