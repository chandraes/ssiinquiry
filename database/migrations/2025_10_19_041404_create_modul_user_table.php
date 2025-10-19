<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('modul_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modul_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            // Relasi ke tabel moduls
            $table->foreign('modul_id')
                  ->references('id')
                  ->on('moduls')
                  ->onDelete('cascade');

            // Relasi ke tabel users
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('modul_user');
    }
};
