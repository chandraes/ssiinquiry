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
        Schema::table('practicum_upload_slots', function (Blueprint $table) {
        // Kolom ini akan menyimpan 'audio_amplitude' atau 'audio_spectrum'
        $table->string('phyphox_experiment_type')->nullable()->after('description');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practicum_upload_slots', function (Blueprint $table) {
            //
        });
    }
};
