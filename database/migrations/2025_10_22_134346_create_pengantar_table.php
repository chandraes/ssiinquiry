<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     * Membuat tabel 'pengantars' dengan kolom id, deskripsi, modul_id, dan timestamps.
     */
    public function up(): void
    {
        Schema::create('pengantar', function (Blueprint $table) {
            // Kolom utama
            $table->id();

            // Kolom yang diminta: Deskripsi (menggunakan tipe text untuk konten panjang)
            $table->text('judul')->required();
            $table->text('pengantar')->required();

            // Kolom yang diminta: Kunci asing ke tabel 'moduls'
            // Diasumsikan ada tabel 'moduls' yang sudah ada
            $table->foreignId('modul_id')
                  ->constrained('moduls') // Mengacu pada tabel 'moduls'
                  ->onDelete('cascade'); // Hapus pengantar jika modul dihapus
            
            // Kolom timestamps (created_at dan updated_at)
            $table->timestamps();
        });
    }

    /**
     * Kembalikan migrasi.
     * Menghapus tabel 'pengantars'.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengantar');
    }
};
