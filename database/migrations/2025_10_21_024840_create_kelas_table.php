<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_id')->constrained('moduls')->onDelete('cascade');
            $table->string('nama_kelas');
            
            // ✅ Ganti guru_id menjadi owner (tetap relasi ke tabel users)
            $table->foreignId('owner')->constrained('users')->onDelete('cascade');
            
            // ✅ Tambahkan kode_join (5 karakter)
            $table->string('kode_join', 5)->unique()->comment('kode unik untuk bergabung ke kelas');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
