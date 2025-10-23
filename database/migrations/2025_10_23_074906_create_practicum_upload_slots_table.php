<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practicum_upload_slots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_module_id')->constrained('sub_modules')->onDelete('cascade');

            // (Multi-bahasa) Label untuk tombol unggah
            $table->json('label');

            // (Multi-bahasa) Petunjuk/nama file
            $table->json('description')->nullable();

            // Untuk mengelompokkan (misal: "Eksperimen 1", "Eksperimen 2")
            $table->string('experiment_group')->nullable();

            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practicum_upload_slots');
    }
};
