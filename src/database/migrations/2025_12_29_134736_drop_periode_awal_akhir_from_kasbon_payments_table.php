<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kasbon_payments', function (Blueprint $table) {
            if (Schema::hasColumn('kasbon_payments', 'periode_awal')) {
                $table->dropColumn('periode_awal');
            }

            if (Schema::hasColumn('kasbon_payments', 'periode_akhir')) {
                $table->dropColumn('periode_akhir');
            }
        });
    }

    public function down(): void
    {
        Schema::table('kasbon_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('kasbon_payments', 'periode_awal')) {
                $table->date('periode_awal')->nullable();
            }

            if (!Schema::hasColumn('kasbon_payments', 'periode_akhir')) {
                $table->date('periode_akhir')->nullable();
            }
        });
    }
};
