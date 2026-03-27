<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        $request->validate([
            'type' => 'required|in:masuk_pagi,keluar_siang,masuk_siang,pulang_kerja,masuk_lembur,pulang_lembur',
            'image_base64' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'accuracy' => 'required|numeric',
            'address' => 'nullable|string',
            'captured_at' => 'nullable|string',
        ]);

        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        if ($request->accuracy > 150) {
            return response()->json([
                'message' => 'Akurasi lokasi terlalu besar, coba tunggu GPS lebih akurat.'
            ], 422);
        }

        $dataUrl = $request->image_base64;

        if (!preg_match('/^data:image\/(jpeg|png);base64,/', $dataUrl)) {
            return response()->json([
                'message' => 'Format foto harus JPEG/PNG base64 dari kamera.'
            ], 422);
        }

        $base64 = substr($dataUrl, strpos($dataUrl, ',') + 1);
        $bin = base64_decode($base64, true);

        if ($bin === false) {
            return response()->json(['message' => 'Foto tidak valid.'], 422);
        }

        if (strlen($bin) > 500 * 1024) {
            return response()->json([
                'message' => 'Foto terlalu besar, coba ulang.'
            ], 422);
        }

        $today = Carbon::today()->toDateString();

        $absen = Absensi::firstOrCreate(
            [
                'name' => $karyawan->nama,
                'tanggal' => $today
            ],
            [
                'name' => $karyawan->nama,
                'tanggal' => $today
            ]
        );

        if ($absen->{$request->type}) {
            return response()->json([
                'message' => 'Absensi untuk ' . str_replace('_', ' ', $request->type) . ' sudah dilakukan.'
            ], 422);
        }

        $filename = 'absensi/' . $karyawan->id_karyawan . '/' . $today . '_' . $request->type . '_' . time() . '.jpg';
        Storage::disk('public')->put($filename, $bin);

        $timeField = $request->type;
        $latField = 'lat_' . $request->type;
        $lngField = 'lng_' . $request->type;
        $accuracyField = 'accuracy_' . $request->type;
        $addressField = 'address_' . $request->type;
        $photoField = 'photo_path_' . $request->type;

        $absen->{$timeField} = now()->format('H:i:s');
        $absen->{$latField} = $request->lat;
        $absen->{$lngField} = $request->lng;
        $absen->{$accuracyField} = $request->accuracy;
        $absen->{$addressField} = $request->address;
        $absen->{$photoField} = $filename;
        $absen->save();

        Log::info('ABSEN TERSIMPAN', [
            'id' => $absen->id,
            'db' => config('database.connections.mysql.database'),
            'name' => $absen->name,
            'tanggal' => $absen->tanggal,
            'type' => $request->type,
            'jam' => $absen->{$request->type},
        ]);

        return response()->json([
            'ok' => true,
            'id' => $absen->id,
            'type' => $request->type,
            'time' => $absen->{$timeField},
            'photo_url' => Storage::url($filename),
            'lat' => $absen->{$latField},
            'lng' => $absen->{$lngField},
            'accuracy' => $absen->{$accuracyField},
            'address' => $absen->{$addressField},
        ]);
    }
    public function history()
    {
        $karyawan = Auth::user()?->karyawan;
        abort_unless($karyawan, 403);

        $absensi = Absensi::where('name', $karyawan->nama)
                        ->orderBy('tanggal', 'desc')
                        ->get();

        return view('mobile.absensi-history', [
            'karyawan' => $karyawan,
            'absensi' => $absensi,
        ]);
    }
    public function approve(Absensi $absensi)
    {
        // Pastikan hanya admin yang bisa melakukan ini
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            abort(403);
        }

        $absensi->update([
            'is_approved' => true,
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Absensi telah disetujui.');
    }
}