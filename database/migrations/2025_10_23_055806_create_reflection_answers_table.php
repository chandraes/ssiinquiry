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
        Schema::create('reflection_answers', function (Blueprint $table) {
           $table->id();
            $table->foreignId('reflection_question_id')->constrained('reflection_questions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');

            // [PERBAIKAN] Menggunakan constrained('kelas')
            $table->foreignId('course_class_id')->constrained('kelas')->onDelete('cascade');

            $table->text('answer_text');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reflection_answers');
    }
};
