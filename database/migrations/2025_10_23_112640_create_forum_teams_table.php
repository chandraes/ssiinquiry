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
        Schema::create('forum_teams', function (Blueprint $table) {
            $table->id();

            // Tetap tahu ini forum yang mana
            $table->foreignId('sub_module_id')->constrained('sub_modules')->onDelete('cascade');

            // [BARU] Tahu ini di kelas yang mana
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');

            // Tahu ini siswa yang mana
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Tahu ini tim apa
            $table->enum('team', ['pro', 'con']);

            $table->timestamps();

            // [DIUBAH] Constraint unik sekarang harus mencakup kelas
            // Seorang siswa hanya bisa satu tim, di satu forum, di satu kelas.
            $table->unique(['sub_module_id', 'kelas_id', 'user_id'], 'forum_team_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_teams');
    }
};
