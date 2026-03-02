<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kasbon_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->date('tanggal_pengajuan')->default(now());

            $table->decimal('nominal', 15, 2);
            $table->integer('tenor');
            $table->decimal('cicilan', 15, 2)->nullable();

            $table->string('alasan_pengajuan')->nullable();

            // Tahap workflow
            $table->string('status_awal')->default('draft');  
            // draft | waiting_staff_verif | waiting_do_awal | approved_do_awal | rejected_do_awal

            $table->string('status_akhir')->default('draft'); 
            // draft | waiting_staff_akhir | waiting_do_akhir | approved_do_akhir | rejected_do_akhir

            // Urgensi (diisi staff kasbon)
            $table->string('prioritas')->nullable(); // rendah/sedang/tinggi
            $table->string('catatan_staff')->nullable();

            // siapa verifikasi staff kasbon
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            // DO approval metadata
            $table->foreignId('approved_awal_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_awal_at')->nullable();

            $table->foreignId('approved_akhir_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_akhir_at')->nullable();

            // link ke loan (kalau sudah jadi pinjaman aktif)
            $table->foreignId('kasbon_loan_id')->nullable()->constrained('kasbon_loans')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kasbon_requests');
    }
};
