<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\KasbonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class KasbonController extends Controller
{
    /**
     * Ambil karyawan_id dari user login
     * jika tidak ada, abort supaya tidak insert NULL.
     */
    private function getKaryawanId(): int
    {
        $user = Auth::user();

        // ✅ kalau user tidak punya relasi karyawan → tidak boleh lanjut
        if (!$user || !$user->karyawan) {
            abort(403, 'Akun ini belum terhubung dengan data karyawan.');
        }

        return $user->karyawan->id;
    }

    public function index()
    {
        abort_unless(Gate::allows('kasbon.request'), 403);

        $karyawanId = $this->getKaryawanId();

        $requests = KasbonRequest::where('karyawan_id', $karyawanId)
            ->latest()
            ->get();

        return view('mobile.kasbon.index', compact('requests'));
    }

    public function create()
    {
        abort_unless(Gate::allows('kasbon.request'), 403);

        // ✅ pastikan karyawan login valid
        $this->getKaryawanId();

        return view('mobile.kasbon.create');
    }

    public function store(Request $request)
    {
        abort_unless(Gate::allows('kasbon.request'), 403);

        $karyawanId = $this->getKaryawanId();

        // ✅ validasi input
        $validated = $request->validate([
            'nominal'         => 'required|numeric|min:10000',
            'tenor'           => 'required|integer|min:1|max:12',
            'alasan_pengajuan'=> 'required|string|max:255',
        ]);

        $cicilan = ceil($validated['nominal'] / $validated['tenor']);

        KasbonRequest::create([
            'karyawan_id'       => $karyawanId,
            'tanggal_pengajuan' => now(),
            'nominal'           => $validated['nominal'],
            'tenor'             => $validated['tenor'],
            'cicilan'           => $cicilan,
            'alasan_pengajuan'  => $validated['alasan_pengajuan'],
            'status_awal'       => 'waiting_staff_verif',
            'status_akhir'      => 'draft',
        ]);

        return redirect()
            ->route('m.kasbon.index')
            ->with('success', 'Pengajuan kasbon berhasil dikirim!');
    }

    public function show($id)
    {
        abort_unless(Gate::allows('kasbon.request'), 403);

        $karyawanId = $this->getKaryawanId();

        // ✅ data-level security → tidak bisa akses kasbon orang lain
        $kasbon = KasbonRequest::where('id', $id)
            ->where('karyawan_id', $karyawanId)
            ->firstOrFail();

        return view('mobile.kasbon.show', compact('kasbon'));
    }
}
