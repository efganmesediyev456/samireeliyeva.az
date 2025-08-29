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
        Schema::create('abouts', function (Blueprint $table) {
            $table->id();
            $table->integer('published_books_count')->nullable();
            $table->integer('certificates_count')->nullable();
            $table->integer('years_in_profession')->nullable();
            $table->string("image")->nullable();
            $table->string("pdf")->nullable();
            $table->timestamps();
        });

        Schema::create('about_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('about_id')->nullable();
            $table->foreign("about_id")->references("id")->on("abouts")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('name')->nullable();
            $table->string('position')->nullable();
            $table->string('biography_title')->nullable();
            $table->text('biography_content')->nullable();
            $table->string('title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->text('description')->nullable();
            $table->unique(['about_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_translations');
        Schema::dropIfExists('abouts');
    }
};