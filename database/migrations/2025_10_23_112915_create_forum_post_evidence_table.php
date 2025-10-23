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
        Schema::create('forum_post_evidence', function (Blueprint $table) {
           $table->id();
            $table->foreignId('forum_post_id')->constrained('forum_posts')->onDelete('cascade');
            $table->foreignId('practicum_submission_id')->constrained('practicum_submissions')->onDelete('cascade');
            $table->timestamps();

            // User hanya bisa melampirkan bukti yang sama satu kali per post
            $table->unique(['forum_post_id', 'practicum_submission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_post_evidence');
    }
};
