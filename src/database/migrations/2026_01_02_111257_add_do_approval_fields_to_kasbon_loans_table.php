<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kasbon_loans', function (Blueprint $table) {
            // status approval tahap awal & akhir
            $table->string('status_awal')->default('draft'); 
            $table->string('status_akhir')->default('draft');

            // urgensi/prioritas (diisi staff kasbon sebelum final approval DO)
            $table->string('prioritas')->nullable();

            // approval metadata
            $table->foreignId('approved_awal_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_awal_at')->nullable();

            $table->foreignId('approved_akhir_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_akhir_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('kasbon_loans', function (Blueprint $table) {
            $table->dropColumn([
                'status_awal',
                'status_akhir',
                'prioritas',
                'approved_awal_by',
                'approved_awal_at',
                'approved_akhir_by',
                'approved_akhir_at',
            ]);
        });
    }
};

