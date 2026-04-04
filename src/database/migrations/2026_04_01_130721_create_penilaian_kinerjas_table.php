<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_kinerjas', function (Blueprint $table) {
            $table->id();

            $table->foreignId('karyawan_id')->constrained('karyawans')->cascadeOnDelete();
            $table->foreignId('penilai_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('periode_kenaikan_gaji', 100);
            $table->date('tanggal_penilaian')->nullable();

            $table->string('disiplin', 1)->nullable();
            $table->string('tanggung_jawab', 1)->nullable();
            $table->string('kualitas_kerja', 1)->nullable();
            $table->string('produktivitas', 1)->nullable();
            $table->string('kerja_sama', 1)->nullable();
            $table->string('inisiatif', 1)->nullable();

            $table->decimal('nilai_akhir', 8, 2)->default(0);
            $table->string('predikat', 2)->nullable();
            $table->bigInteger('nominal_kenaikan_gaji')->default(0);
            $table->text('catatan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_kinerjas');
    }
};