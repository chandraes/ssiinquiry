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
        Schema::create('moduls', function (Blueprint $table) {
            $table->id();
            $table->string('judul_id');
            $table->string('judul_en');
            $table->text('deskripsi_id')->nullable();
            $table->text('deskripsi_en')->nullable();

            // ✅ Tambahan kolom phyphox_id sebagai JSON
            $table->json('phyphox_id')->nullable()->comment('menyimpan daftar id phyphox terkait');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moduls');
    }
};
