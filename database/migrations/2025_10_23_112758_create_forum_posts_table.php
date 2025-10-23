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
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_module_id')->constrained('sub_modules')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Untuk membedakan postingan tim privat vs debat publik
            $table->enum('visibility', ['team', 'public'])->default('public');

            // Tim pemosting ('pro' atau 'con'), diambil saat posting dibuat
            $table->enum('team', ['pro', 'con']);

            // Konten postingan (HTML dari editor WYSIWYG)
            $table->text('content');

            // Untuk threading (balasan)
            $table->unsignedBigInteger('parent_post_id')->nullable();
            $table->foreign('parent_post_id')->references('id')->on('forum_posts')->onDelete('cascade'); // Hapus balasan jika post induk dihapus

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
