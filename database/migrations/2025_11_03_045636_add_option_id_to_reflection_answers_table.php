<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reflection_answers', function (Blueprint $table) {

            // 1. TAMBAHKAN KOLOM UNTUK MENYIMPAN JAWABAN PG
            // Dibuat nullable karena esai tidak akan mengisi ini
            $table->foreignId('reflection_question_option_id')
                  ->nullable()
                  ->after('course_class_id') // Sesuaikan posisi jika perlu
                  ->constrained('reflection_question_options')
                  ->onDelete('set null'); // Jika opsi PG dihapus, jawaban siswa tetap ada

            // 2. UBAH answer_text MENJADI NULLABLE
            // (Penting agar PG bisa disimpan tanpa error)
            $table->text('answer_text')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('reflection_answers', function (Blueprint $table) {
            // Hati-hati saat drop constraint di production
            if (Schema::hasColumn('reflection_answers', 'reflection_question_option_id')) {
                $table->dropForeign(['reflection_question_option_id']);
                $table->dropColumn('reflection_question_option_id');
            }

            // Kembalikan answer_text ke non-nullable (sesuai skema awal)
            $table->text('answer_text')->nullable(false)->change();
        });
    }
};
