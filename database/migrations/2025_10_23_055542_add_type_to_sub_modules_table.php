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
        Schema::table('sub_modules', function (Blueprint $table) {
            // Tipe: 'learning', 'reflection', 'forum'
            // Kita set default 'learning' agar data lama Anda aman
            $table->string('type')->default('learning')->after('modul_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_modules', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
