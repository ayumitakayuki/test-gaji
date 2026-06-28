<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('penilaian_kinerjas', function (Blueprint $table) {
            if (!Schema::hasColumn('penilaian_kinerjas', 'periode_kenaikan_gaji')) {
                $table->string('periode_kenaikan_gaji', 100)->nullable()->after('penilai_user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('penilaian_kinerjas', function (Blueprint $table) {
            $table->dropColumn('periode_kenaikan_gaji');
        });
    }
};
