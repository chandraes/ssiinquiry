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
        Schema::create('reflection_question_options', function (Blueprint $table) {
            $table->id();

            // Relasi ke pertanyaan induk
            $table->foreignId('reflection_question_id')
                  ->constrained('reflection_questions') // terhubung ke tabel reflection_questions
                  ->onDelete('cascade'); // Jika pertanyaan dihapus, opsinya ikut terhapus

            // Teks pilihan (cth: "A. Benar", "B. Salah")
            // Kita pakai 'text' agar bisa panjang
            $table->text('option_text');

            // Penanda kunci jawaban (true/false)
            $table->boolean('is_correct')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reflection_question_options');
    }
};
