<?php

namespace App\Imports;

use App\Models\Karyawan;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KaryawanImport implements ToCollection, WithHeadingRow
{
    protected array $requiredHeaders = [
        'id_karyawan',
        'nama',
        'status',
        'bagian',
        'lokasi',
        'jenis_proyek',
        'gaji_setengah_bulan',
        'gaji_lembur',
        'gaji_harian',
        'uang_makan_lembur_malam',
        'uang_makan_lembur_jalan',
        'potongan_bpjs_kesehatan',
        'potongan_tenaga_kerja',
        'potongan_bpjs_kesehatan_tk',
        'faktor_sj',
        'faktor_sabtu',
        'faktor_minggu',
        'faktor_hari_besar',
    ];

    protected ?string $filename = null;

    public function __construct(?string $filename = null)
    {
        $this->filename = $filename;
    }

    public function collection(Collection $rows)
    {
        if ($this->filename !== 'template-karyawan.xlsx') {
            throw ValidationException::withMessages([
                'file' => 'Nama file tidak valid. Gunakan template-karyawan.xlsx.',
            ]);
        }

        if ($rows->isEmpty()) {
            throw ValidationException::withMessages([
                'file' => 'File kosong. Gunakan template resmi.',
            ]);
        }

        $headers = array_keys($rows->first()->toArray());
        $missing = array_diff($this->requiredHeaders, $headers);

        if (! empty($missing)) {
            throw ValidationException::withMessages([
                'file' => 'Format tidak sesuai. Kolom wajib: ' . implode(', ', $this->requiredHeaders),
            ]);
        }

        foreach ($rows as $row) {
            if (empty($row['id_karyawan']) || empty($row['nama'])) {
                continue;
            }

            $karyawan = Karyawan::updateOrCreate(
                [
                    'id_karyawan' => $row['id_karyawan'],
                ],
                [
                    'nama' => $row['nama'],
                    'status' => $row['status'] ?? null,
                    'bagian' => $row['bagian'] ?? null,
                    'lokasi' => $row['lokasi'] ?? null,
                    'jenis_proyek' => $row['jenis_proyek'] ?? null,
                    'gaji_setengah_bulan' => $row['gaji_setengah_bulan'] ?? null,
                    'gaji_lembur' => $row['gaji_lembur'] ?? null,
                    'gaji_harian' => $row['gaji_harian'] ?? null,
                    'uang_makan_lembur_malam' => $row['uang_makan_lembur_malam'] ?? null,
                    'uang_makan_lembur_jalan' => $row['uang_makan_lembur_jalan'] ?? null,
                    'potongan_bpjs_kesehatan' => $row['potongan_bpjs_kesehatan'] ?? null,
                    'potongan_tenaga_kerja' => $row['potongan_tenaga_kerja'] ?? null,
                    'potongan_bpjs_kesehatan_tk' => $row['potongan_bpjs_kesehatan_tk'] ?? null,
                    'faktor_sj' => $row['faktor_sj'] ?? null,
                    'faktor_sabtu' => $row['faktor_sabtu'] ?? null,
                    'faktor_minggu' => $row['faktor_minggu'] ?? null,
                    'faktor_hari_besar' => $row['faktor_hari_besar'] ?? null,
                ]
            );

            $localPart = Str::of($karyawan->nama)
                ->lower()
                ->replace(' ', '.')
                ->replaceMatches('/[^a-z0-9.]/', '')
                ->trim('.');

            $email = $localPart . '@rku.absensi';

            $user = User::firstOrCreate(
                [
                    'email' => $email,
                ],
                [
                    'name' => $karyawan->nama,
                    'password' => Hash::make('123456'),
                    'karyawan_id' => $karyawan->id,
                ]
            );

            $user->name = $karyawan->nama;
            $user->karyawan_id = $karyawan->id;
            $user->save();

            if (! $user->hasRole('karyawan')) {
                $user->assignRole('karyawan');
            }

            if (array_key_exists('user_id', $karyawan->getAttributes())) {
                $karyawan->user_id = $user->id;
                $karyawan->save();
            }
        }
    }
}