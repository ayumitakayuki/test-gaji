<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PenilaianKinerja extends Model
{
    protected $fillable = [
        'karyawan_id',
        'penilai_user_id',
        'periode_kenaikan_gaji',
        'tanggal_penilaian',
        'disiplin',
        'tanggung_jawab',
        'kualitas_kerja',
        'produktivitas',
        'kerja_sama',
        'inisiatif',
        'nilai_akhir',
        'predikat',
        'nominal_kenaikan_gaji',
        'catatan',
    ];

    protected $casts = [
        'tanggal_penilaian' => 'date',
        'nilai_akhir' => 'decimal:2',
        'nominal_kenaikan_gaji' => 'integer',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function penilai(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penilai_user_id');
    }
}