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
        Schema::create('exam_banners', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->timestamps();
        });

        Schema::create('exam_banner_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_banner_id')->nullable();
            $table->foreign("exam_banner_id")->references("id")->on("exam_banners")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->unique(['exam_banner_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_banner_translations');
        Schema::dropIfExists('exam_banners');
    }
};