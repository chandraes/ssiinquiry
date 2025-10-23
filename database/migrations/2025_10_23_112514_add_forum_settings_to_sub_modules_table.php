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
            $table->json('debate_topic')->nullable()->after('description'); // Multi-bahasa
            $table->text('debate_rules')->nullable()->after('debate_topic'); // Bisa pakai Markdown/HTML sederhana
            $table->timestamp('debate_start_time')->nullable()->after('debate_rules');
            $table->timestamp('debate_end_time')->nullable()->after('debate_start_time');

            // Tanggal untuk fase (bisa dibuat lebih kompleks nanti)
            $table->timestamp('phase1_end_time')->nullable()->after('debate_end_time'); // Akhir Pembukaan
            $table->timestamp('phase2_end_time')->nullable()->after('phase1_end_time'); // Akhir Sanggahan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sub_modules', function (Blueprint $table) {
            $table->dropColumn(['debate_topic', 'debate_rules', 'debate_start_time', 'debate_end_time', 'phase1_end_time', 'phase2_end_time']);
        });
    }
};
