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
        Schema::create('materi_awal', function (Blueprint $table) {
            $table->id();
            
            // Kolom URL (required secara default)
            $table->string('video_liputan')->nullable();
            $table->string('artikel_opini')->nullable();
            $table->string('infografis')->nullable();
            $table->string('regulasi')->nullable();
            
            // Kunci asing ke tabel 'moduls' (required secara default)
            $table->foreignId('modul_id')
                  ->constrained('moduls') 
                  ->onDelete('cascade'); // Hapus materi awal jika modul dihapus
            
            $table->timestamps();
        });
    }

    /**
     * Kembalikan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi_awals');
    }
};
