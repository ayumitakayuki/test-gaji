<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KasbonRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'karyawan_id',
        'tanggal_pengajuan',
        'nominal',
        'tenor',
        'cicilan',
        'alasan_pengajuan',
        'status_awal',
        'status_akhir',
        'prioritas',
        'catatan_staff',
        'verified_by',
        'verified_at',
        'approved_awal_by',
        'approved_awal_at',
        'approved_akhir_by',
        'approved_akhir_at',
        'kasbon_loan_id',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'verified_at' => 'datetime',
        'approved_awal_at' => 'datetime',
        'approved_akhir_at' => 'datetime',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function loan()
    {
        return $this->belongsTo(KasbonLoan::class, 'kasbon_loan_id');
    }
}
