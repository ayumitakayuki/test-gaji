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
        Schema::table('absensis', function (Blueprint $table) {
            $table->boolean('is_declined')->default(false)->after('is_approved');
            $table->text('declined_reason')->nullable()->after('is_declined');
            $table->foreignId('declined_by')->nullable()->after('declined_reason');
            $table->timestamp('declined_at')->nullable()->after('declined_by');
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn([
                'is_declined',
                'declined_reason',
                'declined_by',
                'declined_at',
            ]);
        });
    }
};
