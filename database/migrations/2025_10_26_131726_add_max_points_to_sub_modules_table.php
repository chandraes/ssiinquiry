<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        Schema::table('sub_modules', function (Blueprint $table) {
            // Tambahkan kolom untuk Poin Maksimal
            // Kita beri default 10 agar modul lama tidak error
            $table->integer('max_points')->nullable()->default(10)->after('order');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('sub_modules', function (Blueprint $table) {
            $table->dropColumn('max_points');
        });
    }
};
