<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsensiController extends Controller
{
    public function index()
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        return view('mobile.absensi', [
            'karyawan' => $karyawan,
        ]);
    }

    public function check(Request $request)
    {
        // ✅ 1) VALIDASI: tambah lokasi + akurasi + captured_at
        $request->validate([
            'type' => 'required|in:masuk_pagi,keluar_siang,masuk_siang,pulang_kerja,masuk_lembur,pulang_lembur',
            'image_base64' => 'required|string',

            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'accuracy' => 'required|numeric',
            'captured_at' => 'nullable|string',
        ]);

        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        // (opsional) batas akurasi lokasi biar gak ngaco
        if ($request->accuracy > 150) {
            return response()->json(['message' => 'Akurasi lokasi terlalu besar, coba tunggu GPS lebih akurat.'], 422);
        }

        // ✅ 2) DECODE BASE64 + SIMPAN FILE (selfie proof)
        $dataUrl = $request->image_base64;

        // Terima jpeg atau png biar aman
        if (!preg_match('/^data:image\/(jpeg|png);base64,/', $dataUrl)) {
            return response()->json(['message' => 'Format foto harus JPEG/PNG base64 dari kamera (bukan upload galeri).'], 422);
        }

        $base64 = substr($dataUrl, strpos($dataUrl, ',') + 1);
        $bin = base64_decode($base64, true);

        if ($bin === false) {
            return response()->json(['message' => 'Foto tidak valid.'], 422);
        }

        // batasi ukuran (misal max 500KB) biar ringan
        if (strlen($bin) > 500 * 1024) {
            return response()->json(['message' => 'Foto terlalu besar, coba ulang (kamera akan dikompres).'], 422);
        }

        $today = Carbon::today()->toDateString();

        $absen = Absensi::firstOrCreate(
            ['name' => $karyawan->nama, 'tanggal' => $today],
            ['name' => $karyawan->nama, 'tanggal' => $today]
        );

        // nama file: absensi/{id}/{tanggal}_{type}_{timestamp}.jpg
        $filename = 'absensi/' . $karyawan->id_karyawan . '/' . $today . '_' . $request->type . '_' . time() . '.jpg';

        // simpan ke storage public
        Storage::disk('public')->put($filename, $bin);

        // ✅ 3) SIMPAN JAM + LOKASI + PATH FOTO
        $absen->{$request->type} = now()->format('H:i:s');
        $absen->lat = $request->lat;
        $absen->lng = $request->lng;
        $absen->accuracy = $request->accuracy;
        $absen->photo_path = $filename;
        $absen->save();

        return response()->json([
            'ok' => true,
            'type' => $request->type,
            'time' => $absen->{$request->type},

            // biar bisa ditampilkan di UI kalau perlu
            'photo_url' => Storage::url($filename),
            'lat' => $absen->lat,
            'lng' => $absen->lng,
            'accuracy' => $absen->accuracy,
        ]);
    }
}