<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE rekap_gaji_periods
            MODIFY status_do ENUM('draft', 'waiting_do', 'approved_do', 'rejected_do')
            NOT NULL DEFAULT 'draft'
        ");
    }

    public function down(): void
    {
        DB::statement("
            ALTER TABLE rekap_gaji_periods
            MODIFY status_do ENUM('waiting_do', 'approved_do', 'rejected_do')
            NOT NULL DEFAULT 'waiting_do'
        ");
    }
};