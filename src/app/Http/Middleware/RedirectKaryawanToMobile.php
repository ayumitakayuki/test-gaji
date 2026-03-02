<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RedirectKaryawanToMobile
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user instanceof User) {
            return $next($request);
        }

        // hanya karyawan yang diarahkan
        if ($user->hasRole('karyawan') && $request->is('admin', 'admin/*')) {
            return redirect()->route('m.kasbon.index');
        }

        return $next($request);
    }
}
