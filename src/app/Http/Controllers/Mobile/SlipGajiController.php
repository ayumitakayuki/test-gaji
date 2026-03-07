<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use App\Exports\SlipGajiPdfExport;
use Illuminate\Support\Facades\Auth;

class SlipGajiController extends Controller
{
    public function index()
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);
        // sesuaikan field yang kamu pakai di tabel gajis:
        $slips = Gaji::query()
            ->where('id_karyawan', $karyawan->id_karyawan)
            ->orderByDesc('periode_akhir')
            ->get();

        return view('mobile.slip.index', compact('slips', 'karyawan'));
    }

    public function show($id)
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);
        $gaji = Gaji::with('details')->findOrFail($id);

        // pastikan slip ini milik dia
        abort_unless($gaji->id_karyawan === $karyawan->id_karyawan, 403);

        return view('mobile.slip.show', compact('gaji'));
    }

    public function pdf($id, SlipGajiPdfExport $export)
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        $gaji = Gaji::findOrFail($id);
        abort_unless($gaji->id_karyawan === $karyawan->id_karyawan, 403);

        // reuse exporter yang sudah ada :contentReference[oaicite:9]{index=9}
        return $export(request(), $id);
    }
}