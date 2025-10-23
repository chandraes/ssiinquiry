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
        Schema::table('moduls', function (Blueprint $table) {
            // 1. Tambahkan kolom JSON baru
            $table->json('judul')->after('phyphox_id'); // Sesuaikan 'after'
            $table->json('deskripsi')->nullable()->after('judul');

            // 2. Hapus kolom-kolom lama
            $table->dropColumn(['judul_id', 'judul_en', 'deskripsi_id', 'deskripsi_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('moduls', function (Blueprint $table) {
            $table->dropColumn(['judul', 'deskripsi']);

            $table->string('judul_id');
            $table->string('judul_en');
            $table->text('deskripsi_id')->nullable();
            $table->text('deskripsi_en')->nullable();
        });
    }
};
