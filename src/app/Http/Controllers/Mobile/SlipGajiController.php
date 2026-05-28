<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Gaji;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class SlipGajiController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $karyawan = $user->karyawan;

        abort_if(!$karyawan, 404);

        $slips = Gaji::with('details')
            ->where('id_karyawan', $karyawan->id_karyawan)
            ->orderByDesc('periode_akhir')
            ->get();

        return view('mobile.slip.index', compact('slips'));
    }

    public function pdf($id)
    {
        $user = Auth::user();
        $karyawan = $user->karyawan;

        abort_if(!$karyawan, 404);

        $gaji = Gaji::with('details')->findOrFail($id);

        abort_unless($gaji->id_karyawan === $karyawan->id_karyawan, 403);

        $pdf = Pdf::loadView('exports.slip-gaji-pdf', compact('gaji'));

        return $pdf->stream('Slip-Gaji-' . $gaji->nama . '.pdf');
    }
}