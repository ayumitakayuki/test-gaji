<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RekapTransferPermata extends Model
{
    protected $table   = 'rekap_transfer_permatas';
    public    $timestamps = true;

    // Karena sudah menggunakan guarded = [], kolom baru otomatis bisa diisi
    protected $guarded = [];

    // Cast approved_do_at ke datetime
    protected $casts = [
        'approved_do_at' => 'datetime',
    ];

    public function rows()
    {
        return $this->hasMany(RekapTransferPermataRow::class, 'rekap_transfer_permata_id');
    }

    // Relasi ke user yang menyetujui rekap (Direktur Operasional)
    public function approvedDo()
    {
        return $this->belongsTo(User::class, 'approved_do_by');
    }
}