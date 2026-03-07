<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('mobile.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // pastikan user memiliki peran karyawan, jika tidak logout
            if (! ($user instanceof \App\Models\User) || ! $user->hasRole('karyawan')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan karyawan.',
                ]);
            }

            // Catat waktu login agar rekam jejak login tersimpan
            $user->last_login_at = now();
            $user->save();

            // Alihkan ke dashboard (bukan ke kasbon)
            return redirect()->route('m.dashboard');
        }

        return back()->withErrors([
            'email' => 'Email / password salah.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('m.login');
    }
}