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
        Schema::create('learning_materials', function (Blueprint $table) {
           $table->id();
            // Relasi ke sub-modul
            $table->foreignId('sub_module_id')->constrained('sub_modules')->onDelete('cascade');

            // [MULTI-BAHASA] Kolom title diubah menjadi JSON
            $table->json('title');

            // Tipe materi: video, article, infographic, regulation
            $table->string('type');

            // [MULTI-BAHASA] Kolom content diubah menjadi JSON
            // Ini membuatnya fleksibel:
            // - video: {"url": "youtube.com/..."}
            // - article: {"id": "Teks...", "en": "Text..."}
            // - infographic/regulation: {"path": "files/file.pdf"}
            $table->json('content');

            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('learning_materials');
    }
};
