<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;
class Karyawan extends Model
{
    // IKUT MIGRATION: PK = 'id' (default), BUKAN 'id_karyawan'
    // Hapus: protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'id_karyawan', // tetap boleh simpan kode "KR-..." sebagai identifier bisnis
        'nama',
        'status',
        'lokasi',
        'bagian',
        'jenis_proyek',
        'gaji_setengah_bulan',
        'gaji_lembur',
        'gaji_harian',
        'uang_makan_lembur_malam',
        'uang_makan_lembur_jalan',
        'jenis_bpjs',
        'potongan_bpjs_kesehatan',
        'potongan_tenaga_kerja',
        'potongan_bpjs_kesehatan_tk',
        'tanggungan_perusahaan_bpjs_kesehatan',
        'tanggungan_perusahaan_bpjs_tk',
        'tanggungan_perusahaan_bpjs_kesehatan_tk',
        'total_iuran_bpjs',
        'faktor_sj',
        'faktor_sabtu',
        'faktor_minggu',
        'faktor_hari_besar',
    ];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function absensis(): HasMany
    {
        // kalau tabel absensis punya fk karyawan_id numerik, pakai ini:
        return $this->hasMany(Absensi::class, 'karyawan_id', 'id');
        // kalau memang belum ada kolom itu dan masih lewat 'nama', biarkan sesuai skema tabelmu
    }

    public static function generateNextIdKaryawan(): string
    {
        $lastId = static::query()
            ->whereNotNull('id_karyawan')
            ->whereRaw("id_karyawan REGEXP '^[0-9]+$'")
            ->orderByRaw('CAST(id_karyawan AS UNSIGNED) DESC')
            ->value('id_karyawan');

        $nextNumber = $lastId ? ((int) $lastId + 1) : 1;

        $length = max(3, strlen((string) $nextNumber));

        return str_pad((string) $nextNumber, $length, '0', STR_PAD_LEFT);
    }

    public const UMR_BPJS = 4900000;

    public static function hitungPotonganBpjs(?string $jenisBpjs): array
    {
        $umr = self::UMR_BPJS;

        // POTONGAN KARYAWAN
        $bpjsKesKaryawan = round($umr * 0.01, 0);
        $bpjsTkKaryawan  = round($umr * 0.03, 0);

        // TANGGUNGAN PERUSAHAAN
        $bpjsKesPerusahaan = round($umr * 0.04, 0);
        $bpjsTkPerusahaan  = round($umr * 0.0624, 0);

        return match ($jenisBpjs) {

            'bpjs_kesehatan' => [

                'potongan_bpjs_kesehatan' => $bpjsKesKaryawan,
                'potongan_tenaga_kerja' => 0,
                'potongan_bpjs_kesehatan_tk' => 0,

                'tanggungan_perusahaan_bpjs_kesehatan' => $bpjsKesPerusahaan,
                'tanggungan_perusahaan_bpjs_tk' => 0,
                'tanggungan_perusahaan_bpjs_kesehatan_tk' => 0,

                'total_iuran_bpjs' =>
                    $bpjsKesKaryawan +
                    $bpjsKesPerusahaan,
            ],

            'bpjs_tenaga_kerja' => [

                'potongan_bpjs_kesehatan' => 0,
                'potongan_tenaga_kerja' => $bpjsTkKaryawan,
                'potongan_bpjs_kesehatan_tk' => 0,

                'tanggungan_perusahaan_bpjs_kesehatan' => 0,
                'tanggungan_perusahaan_bpjs_tk' => $bpjsTkPerusahaan,
                'tanggungan_perusahaan_bpjs_kesehatan_tk' => 0,

                'total_iuran_bpjs' =>
                    $bpjsTkKaryawan +
                    $bpjsTkPerusahaan,
            ],

            'bpjs_kesehatan_tk' => [

                'potongan_bpjs_kesehatan' => 0,
                'potongan_tenaga_kerja' => 0,

                'potongan_bpjs_kesehatan_tk' =>
                    $bpjsKesKaryawan +
                    $bpjsTkKaryawan,

                'tanggungan_perusahaan_bpjs_kesehatan' => 0,
                'tanggungan_perusahaan_bpjs_tk' => 0,

                'tanggungan_perusahaan_bpjs_kesehatan_tk' =>
                    $bpjsKesPerusahaan +
                    $bpjsTkPerusahaan,

                'total_iuran_bpjs' =>
                    $bpjsKesKaryawan +
                    $bpjsTkKaryawan +
                    $bpjsKesPerusahaan +
                    $bpjsTkPerusahaan,
            ],

            default => [

                'potongan_bpjs_kesehatan' => 0,
                'potongan_tenaga_kerja' => 0,
                'potongan_bpjs_kesehatan_tk' => 0,

                'tanggungan_perusahaan_bpjs_kesehatan' => 0,
                'tanggungan_perusahaan_bpjs_tk' => 0,
                'tanggungan_perusahaan_bpjs_kesehatan_tk' => 0,

                'total_iuran_bpjs' => 0,
            ],
        };
    }

    protected static function booted()
    {
        static::saving(function ($karyawan) {
            if (empty($karyawan->id_karyawan)) {
                $karyawan->id_karyawan = static::generateNextIdKaryawan();
            }

            $jumlahHariKerjaSebulan = 21;

            if ($karyawan->status === 'harian tetap') {
                $gajiSetengahBulan = (float) ($karyawan->gaji_setengah_bulan ?? 0);

                $karyawan->gaji_harian = null;
                $karyawan->gaji_lembur = $gajiSetengahBulan > 0
                    ? round(($gajiSetengahBulan * 2) / 174, 0)
                    : null;
            }

            if ($karyawan->status === 'harian lepas') {
                $gajiHarian = (float) ($karyawan->gaji_harian ?? 0);

                $karyawan->gaji_setengah_bulan = null;
                $karyawan->gaji_lembur = $gajiHarian > 0
                    ? round(($gajiHarian * $jumlahHariKerjaSebulan) / 174, 0)
                    : null;
            }

            $karyawan->uang_makan_lembur_malam = 15000;
            $karyawan->uang_makan_lembur_jalan = 25000;

            $karyawan->faktor_sj = 1.5;
            $karyawan->faktor_sabtu = 1.5;
            $karyawan->faktor_minggu = 2;
            $karyawan->faktor_hari_besar = 2;

            if (empty($karyawan->jenis_bpjs)) {
                $karyawan->jenis_bpjs = 'tanpa_bpjs';
            }

            foreach (static::hitungPotonganBpjs($karyawan->jenis_bpjs) as $field => $value) {
                $karyawan->{$field} = $value;
            }
        });
    }

    public function setJenisProyekAttribute($value)
    {
        $this->attributes['jenis_proyek'] = is_string($value) ? trim($value) : $value;
    }

    public function setLokasiAttribute($value)
    {
        $v = is_string($value) ? strtolower(trim($value)) : $value;
        $this->attributes['lokasi'] = in_array($v, ['workshop','proyek'], true) ? $v : null;
    }

    public function rekaps()
    {
        // IKUT MIGRATION: fk = absensi_rekaps.karyawan_id (bigint) -> karyawans.id (bigint)
        return $this->hasMany(AbsensiRekap::class, 'karyawan_id', 'id');
    }
}
