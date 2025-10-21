<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('kelas_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('pro_kontra_id', ['1', '2'])->nullable()
                  ->comment('1 = pro, 2 = kontra');
            $table->timestamps();

            // Tambahkan constraint unik agar user tidak bisa terdaftar dua kali di kelas yang sama
            $table->unique(['kelas_id', 'user_id'], 'kelas_user_unique');
        });
    }

    /**
     * Rollback migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_users');
    }
};
