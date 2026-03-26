<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Perizinan extends Model
{
    protected $fillable = [
        'karyawan_id', 'jenis', 'tanggal_mulai', 'tanggal_selesai',
        'keterangan', 'bukti_path', 'is_approved', 'approved_by', 'approved_at',
    ];

    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}