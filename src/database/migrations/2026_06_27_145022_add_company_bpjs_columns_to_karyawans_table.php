<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {

            $table->bigInteger('tanggungan_perusahaan_bpjs_kesehatan')
                ->default(0)
                ->after('potongan_bpjs_kesehatan');

            $table->bigInteger('tanggungan_perusahaan_bpjs_tk')
                ->default(0)
                ->after('potongan_tenaga_kerja');

            $table->bigInteger('tanggungan_perusahaan_bpjs_kesehatan_tk')
                ->default(0)
                ->after('potongan_bpjs_kesehatan_tk');

            $table->bigInteger('total_iuran_bpjs')
                ->default(0)
                ->after('tanggungan_perusahaan_bpjs_kesehatan_tk');
        });
    }

    public function down(): void
    {
        Schema::table('karyawans', function (Blueprint $table) {

            $table->dropColumn([
                'tanggungan_perusahaan_bpjs_kesehatan',
                'tanggungan_perusahaan_bpjs_tk',
                'tanggungan_perusahaan_bpjs_kesehatan_tk',
                'total_iuran_bpjs',
            ]);
        });
    }
};