<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'tujuan_moduls' dengan kolom id, tujuan, modul_id, dan timestamps.
     */
    public function up(): void
    {
        Schema::create('tujuan_modul', function (Blueprint $table) {
            // Kolom utama
            $table->id();

            // Kolom yang diminta: Tujuan (menggunakan tipe text untuk deskripsi tujuan)
            $table->text('judul')->required();
            $table->text('tujuan')->required();;

            // Kolom yang diminta: Kunci asing ke tabel 'moduls'
            // Diasumsikan ada tabel 'moduls' yang sudah ada
            $table->foreignId('modul_id')
                  ->constrained('moduls') // Mengacu pada tabel 'moduls'
                  ->onDelete('cascade');

            // Kolom timestamps (created_at dan updated_at)
            $table->timestamps();
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'tujuan_moduls'.
     */
    public function down(): void
    {
        Schema::dropIfExists('tujuan_modul');
    }
};
