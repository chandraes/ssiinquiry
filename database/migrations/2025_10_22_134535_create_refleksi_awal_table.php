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
        Schema::create('refleksi_awal', function (Blueprint $table) {
            $table->id();
            
            // Kolom pertanyaan (required secara default)
            $table->text('pertanyaan');
            
            // Kunci asing ke tabel 'moduls' (required secara default)
            $table->foreignId('modul_id')
                  ->constrained('moduls') 
                  ->onDelete('cascade'); // Hapus refleksi jika modul dihapus
            
            $table->timestamps();
        });
    }

    /**
     * Kembalikan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('refleksi_awal');
    }
};
