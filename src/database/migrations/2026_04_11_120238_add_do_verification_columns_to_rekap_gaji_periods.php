<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekap_gaji_periods', function (Blueprint $table) {
            $table->enum('status_do', ['waiting_do', 'approved_do', 'rejected_do'])
                  ->default('waiting_do')
                  ->after('created_by');
            $table->unsignedBigInteger('approved_do_by')->nullable()->after('status_do');
            $table->timestamp('approved_do_at')->nullable()->after('approved_do_by');
        });
    }

    public function down(): void
    {
        Schema::table('rekap_gaji_periods', function (Blueprint $table) {
            $table->dropColumn(['status_do', 'approved_do_by', 'approved_do_at']);
        });
    }
};