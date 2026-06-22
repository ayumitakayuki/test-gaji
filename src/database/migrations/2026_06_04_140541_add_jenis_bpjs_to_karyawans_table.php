<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->enum('jenis_bpjs', [
                'tanpa_bpjs',
                'bpjs_kesehatan',
                'bpjs_tenaga_kerja',
                'bpjs_kesehatan_tk',
            ])->default('tanpa_bpjs')->after('uang_makan_lembur_jalan');
        });
    }

    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {
            $table->dropColumn('jenis_bpjs');
        });
    }
};