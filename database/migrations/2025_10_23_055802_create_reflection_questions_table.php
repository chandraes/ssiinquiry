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
        Schema::create('reflection_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_module_id')->constrained('sub_modules')->onDelete('cascade');
            $table->json('question_text'); // Multi-bahasa (ID/EN)
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reflection_questions');
    }
};
