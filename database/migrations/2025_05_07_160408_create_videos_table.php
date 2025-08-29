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
      // For videos table
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->string('video_url');
            $table->string('thumbnail')->nullable();
            $table->date('date')->nullable();
            $table->tinyInteger("type")->default(1);
            $table->timestamps();
        });

        // For video translations table
        Schema::create('video_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->string('locale');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unique(['video_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_translations');
        Schema::dropIfExists('videos');
    }
};
