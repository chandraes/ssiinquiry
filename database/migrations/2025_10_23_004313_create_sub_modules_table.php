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
        Schema::create('sub_modules', function (Blueprint $table) {
           $table->id();
            // Relasi ke modul utama
            $table->foreignId('modul_id')->constrained('moduls')->onDelete('cascade');

            // [MULTI-BAHASA] Kolom title diubah menjadi JSON
            $table->json('title');

            // [MULTI-BAHASA] Kolom description diubah menjadi JSON
            $table->json('description')->nullable();

            $table->integer('order')->default(0); // Untuk pengurutan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_modules');
    }
};
