<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('mobile.password-edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Ambil instance user yang sebenarnya
        $user = User::find(Auth::id());

        // Periksa password lama
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'Password lama salah',
            ]);
        }

        // Hash password baru menggunakan Bcrypt secara eksplisit
        $newHashed = bcrypt($request->password);
        $user->forceFill(['password' => $newHashed])->save();

        return back()->with('status', 'Password berhasil diubah');
    }
}