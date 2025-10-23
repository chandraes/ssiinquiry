<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('practicum_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practicum_upload_slot_id')->constrained('practicum_upload_slots')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');

            // Menggunakan nama tabel 'kelas' Anda
            $table->foreignId('course_class_id')->constrained('kelas')->onDelete('cascade');

            // Path ke file CSV di storage
            $table->string('file_path');
            $table->string('original_filename');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('practicum_submissions');
    }
};
