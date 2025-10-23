<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('moduls', function (Blueprint $table) {
            // Tambahkan kolom untuk menyimpan path gambar
            $table->string('image')->nullable()->after('deskripsi');
        });
    }

    public function down(): void
    {
        Schema::table('moduls', function (Blueprint $table) {
            $table->dropColumn('image');
        });
    }
};
