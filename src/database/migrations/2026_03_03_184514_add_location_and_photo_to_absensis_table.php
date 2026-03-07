<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // lokasi (lat/lng)
            $table->decimal('lat', 10, 7)->nullable()->after('tanggal');
            $table->decimal('lng', 10, 7)->nullable()->after('lat');

            // akurasi GPS (meter)
            $table->float('accuracy')->nullable()->after('lng');

            // path file selfie proof di storage/public
            $table->string('photo_path')->nullable()->after('accuracy');
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn(['lat', 'lng', 'accuracy', 'photo_path']);
        });
    }
};