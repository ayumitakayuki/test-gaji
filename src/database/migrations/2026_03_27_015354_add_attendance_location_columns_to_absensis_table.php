<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            // Masuk pagi
            $table->decimal('lat_masuk_pagi', 10, 7)->nullable()->after('masuk_pagi');
            $table->decimal('lng_masuk_pagi', 10, 7)->nullable()->after('lat_masuk_pagi');
            $table->float('accuracy_masuk_pagi')->nullable()->after('lng_masuk_pagi');
            $table->text('address_masuk_pagi')->nullable()->after('accuracy_masuk_pagi');
            $table->string('photo_path_masuk_pagi')->nullable()->after('address_masuk_pagi');

            // Keluar siang
            $table->decimal('lat_keluar_siang', 10, 7)->nullable()->after('keluar_siang');
            $table->decimal('lng_keluar_siang', 10, 7)->nullable()->after('lat_keluar_siang');
            $table->float('accuracy_keluar_siang')->nullable()->after('lng_keluar_siang');
            $table->text('address_keluar_siang')->nullable()->after('accuracy_keluar_siang');
            $table->string('photo_path_keluar_siang')->nullable()->after('address_keluar_siang');

            // Masuk siang
            $table->decimal('lat_masuk_siang', 10, 7)->nullable()->after('masuk_siang');
            $table->decimal('lng_masuk_siang', 10, 7)->nullable()->after('lat_masuk_siang');
            $table->float('accuracy_masuk_siang')->nullable()->after('lng_masuk_siang');
            $table->text('address_masuk_siang')->nullable()->after('accuracy_masuk_siang');
            $table->string('photo_path_masuk_siang')->nullable()->after('address_masuk_siang');

            // Pulang kerja
            $table->decimal('lat_pulang_kerja', 10, 7)->nullable()->after('pulang_kerja');
            $table->decimal('lng_pulang_kerja', 10, 7)->nullable()->after('lat_pulang_kerja');
            $table->float('accuracy_pulang_kerja')->nullable()->after('lng_pulang_kerja');
            $table->text('address_pulang_kerja')->nullable()->after('accuracy_pulang_kerja');
            $table->string('photo_path_pulang_kerja')->nullable()->after('address_pulang_kerja');

            // Masuk lembur
            $table->decimal('lat_masuk_lembur', 10, 7)->nullable()->after('masuk_lembur');
            $table->decimal('lng_masuk_lembur', 10, 7)->nullable()->after('lat_masuk_lembur');
            $table->float('accuracy_masuk_lembur')->nullable()->after('lng_masuk_lembur');
            $table->text('address_masuk_lembur')->nullable()->after('accuracy_masuk_lembur');
            $table->string('photo_path_masuk_lembur')->nullable()->after('address_masuk_lembur');

            // Pulang lembur
            $table->decimal('lat_pulang_lembur', 10, 7)->nullable()->after('pulang_lembur');
            $table->decimal('lng_pulang_lembur', 10, 7)->nullable()->after('lat_pulang_lembur');
            $table->float('accuracy_pulang_lembur')->nullable()->after('lng_pulang_lembur');
            $table->text('address_pulang_lembur')->nullable()->after('accuracy_pulang_lembur');
            $table->string('photo_path_pulang_lembur')->nullable()->after('address_pulang_lembur');
        });
    }

    public function down(): void
    {
        Schema::table('absensis', function (Blueprint $table) {
            $table->dropColumn([
                'lat_masuk_pagi', 'lng_masuk_pagi', 'accuracy_masuk_pagi', 'address_masuk_pagi', 'photo_path_masuk_pagi',
                'lat_keluar_siang', 'lng_keluar_siang', 'accuracy_keluar_siang', 'address_keluar_siang', 'photo_path_keluar_siang',
                'lat_masuk_siang', 'lng_masuk_siang', 'accuracy_masuk_siang', 'address_masuk_siang', 'photo_path_masuk_siang',
                'lat_pulang_kerja', 'lng_pulang_kerja', 'accuracy_pulang_kerja', 'address_pulang_kerja', 'photo_path_pulang_kerja',
                'lat_masuk_lembur', 'lng_masuk_lembur', 'accuracy_masuk_lembur', 'address_masuk_lembur', 'photo_path_masuk_lembur',
                'lat_pulang_lembur', 'lng_pulang_lembur', 'accuracy_pulang_lembur', 'address_pulang_lembur', 'photo_path_pulang_lembur',
            ]);
        });
    }
};