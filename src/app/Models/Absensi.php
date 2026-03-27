<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'name',
        'tanggal',
        'masuk_pagi',
        'keluar_siang',
        'masuk_siang',
        'pulang_kerja',
        'masuk_lembur',
        'pulang_lembur',
        'is_approved',
        'approved_by',
        'approved_at',

        'lat_masuk_pagi',
        'lng_masuk_pagi',
        'accuracy_masuk_pagi',
        'address_masuk_pagi',
        'photo_path_masuk_pagi',

        'lat_keluar_siang',
        'lng_keluar_siang',
        'accuracy_keluar_siang',
        'address_keluar_siang',
        'photo_path_keluar_siang',

        'lat_masuk_siang',
        'lng_masuk_siang',
        'accuracy_masuk_siang',
        'address_masuk_siang',
        'photo_path_masuk_siang',

        'lat_pulang_kerja',
        'lng_pulang_kerja',
        'accuracy_pulang_kerja',
        'address_pulang_kerja',
        'photo_path_pulang_kerja',

        'lat_masuk_lembur',
        'lng_masuk_lembur',
        'accuracy_masuk_lembur',
        'address_masuk_lembur',
        'photo_path_masuk_lembur',

        'lat_pulang_lembur',
        'lng_pulang_lembur',
        'accuracy_pulang_lembur',
        'address_pulang_lembur',
        'photo_path_pulang_lembur',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'name', 'nama');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}