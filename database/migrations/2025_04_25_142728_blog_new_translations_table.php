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
        Schema::create('blog_new_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('blog_new_id')->nullable();
            $table->foreign("blog_new_id")->references("id")->on("blog_news")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('slug')->nullable();
            $table->unique(['blog_new_id', 'locale']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("blog_new_translations");
    }
};
