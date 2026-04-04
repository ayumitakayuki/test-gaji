<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\PenilaianKinerja;
use Illuminate\Support\Facades\Auth;

class PenilaianKinerjaController extends Controller
{
    public function index()
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        $penilaians = PenilaianKinerja::query()
            ->where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal_penilaian')
            ->get();

        return view('mobile.penilaian-kinerja.index', compact('penilaians', 'karyawan'));
    }

    public function show($id)
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        $penilaian = PenilaianKinerja::findOrFail($id);

        abort_unless($penilaian->karyawan_id === $karyawan->id, 403);

        return view('mobile.penilaian-kinerja.show', compact('penilaian', 'karyawan'));
    }
}