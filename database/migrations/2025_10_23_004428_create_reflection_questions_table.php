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
        Schema::create('reflection_questions', function (Blueprint $table) {
            $table->id();
            // Relasi ke pertanyaan
            $table->foreignId('reflection_question_id')->constrained('reflection_questions')->onDelete('cascade');

            // Relasi ke murid (asumsi tabel 'users')
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Relasi ke kelas (asumsi tabel 'course_classes')
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            // Jawaban murid (TIDAK PERLU JSON)
            $table->text('answer_text');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reflection_questions');
    }
};
