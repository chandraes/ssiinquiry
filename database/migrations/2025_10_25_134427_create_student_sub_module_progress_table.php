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
        Schema::create('student_sub_module_progress', function (Blueprint $table) {
            $table->id();

            // Siapa yang menyelesaikan?
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Sub-modul apa yang diselesaikan?
            $table->foreignId('sub_module_id')->constrained('sub_modules')->onDelete('cascade');

            // Di kelas mana dia menyelesaikannya?
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            // Kapan diselesaikan? (null berarti belum selesai)
            $table->timestamp('completed_at')->nullable();

            $table->timestamps(); // created_at, updated_at

            // Pastikan kombinasi user, sub_module, dan kelas unik
            $table->unique(['user_id', 'sub_module_id', 'kelas_id'], 'student_progress_unique');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_sub_module_progress');
    }
};
