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
        Schema::table('kelas', function (Blueprint $table) {
            // 1. Ubah tipe kolom 'nama_kelas' menjadi JSON
            // Kita gunakan change() agar datanya tidak hilang jika memungkinkan
            // Pastikan Anda memiliki package 'doctrine/dbal'
            // composer require doctrine/dbal
            $table->json('nama_kelas')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Ini akan kehilangan data bahasa, kembali ke string
            $table->string('nama_kelas')->change();
        });
    }
};
