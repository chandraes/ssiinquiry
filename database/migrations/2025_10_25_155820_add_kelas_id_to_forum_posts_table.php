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
       Schema::table('forum_posts', function (Blueprint $table) {
            // Tambahkan kolom ini, idealnya setelah user_id
            $table->foreignId('kelas_id')
                  ->constrained('kelas') // Pastikan nama tabel Anda 'kelas'
                  ->onDelete('cascade')
                  ->after('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('forum_posts', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};
