<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Perizinan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerizinanController extends Controller
{
    public function index()
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        $perizinan = Perizinan::where('karyawan_id', $karyawan->id)
            ->orderByDesc('tanggal_mulai')
            ->get();

        return view('mobile.perizinan', [
            'karyawan'  => $karyawan,
            'perizinan' => $perizinan,
        ]);
    }

    // Formulir pengajuan izin
    public function create()
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        return view('mobile.perizinan-create');
    }

    // Simpan pengajuan izin
    public function store(Request $request)
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        $validated = $request->validate([
            'jenis'           => 'required|in:sakit,izin,cuti,berduka,tanpa_alasan',
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'keterangan'      => 'nullable|string',
            'bukti_path'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $filePath = $request->file('bukti_path')?->store('bukti-perizinan', 'public');

        Perizinan::create([
            'karyawan_id'     => $karyawan->id,
            'jenis'           => $validated['jenis'],
            'tanggal_mulai'   => $validated['tanggal_mulai'],
            'tanggal_selesai' => $validated['tanggal_selesai'],
            'keterangan'      => $validated['keterangan'] ?? null,
            'bukti_path'      => $filePath,
            'is_approved'     => false,
        ]);

        return redirect()->route('m.perizinan.index')
                         ->with('success', 'Permohonan izin berhasil dikirim.');
    }

// Approve permohonan (hanya admin)
public function approve(int $id)
{
    $user = Auth::user();
    abort_unless($user && $user->role === 'admin', 403);

    $izin = Perizinan::findOrFail($id);
    $izin->update([
        'is_approved' => true,
        'approved_by' => $user?->id,
        'approved_at' => now(),
    ]);
}
}