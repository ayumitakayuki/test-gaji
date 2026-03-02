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

            // ✅ Pastikan user = App\Models\User (agar hasRole dikenali IDE)
            if (! ($user instanceof \App\Models\User) || ! $user->hasRole('karyawan')) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun ini bukan karyawan.',
                ]);
            }

            return redirect()->route('m.kasbon.index');
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
