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
        Schema::create('gallery_videos', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('gallery_video_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gallery_video_id')->nullable();
            $table->foreign("gallery_video_id")->references("id")->on("gallery_videos")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('slug')->nullable();
            $table->unique(['gallery_video_id', 'locale']);
        });

        Schema::create('gallery_video_media', function (Blueprint $table) {
            $table->id();
            $table->string("file")->nullable();
            $table->tinyInteger("status")->default(0);
            $table->integer("order")->default(0);
            $table->unsignedBigInteger("gallery_video_id")->nullable();
            $table->foreign("gallery_video_id")->references("id")->on("gallery_videos")->noActionOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gallery_videos');
    }
};