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
        Schema::table('student_sub_module_progress', function (Blueprint $table) {
            // Kolom untuk nilai dari guru
            $table->integer('score')->nullable()->after('completed_at');

            // Kolom untuk umpan balik kualitatif dari guru
            $table->text('feedback')->nullable()->after('score');
        });
    }

    /**
     * Batalkan migrasi.
     */
    public function down(): void
    {
        Schema::table('student_sub_module_progress', function (Blueprint $table) {
            $table->dropColumn(['score', 'feedback']);
        });
    }
};
