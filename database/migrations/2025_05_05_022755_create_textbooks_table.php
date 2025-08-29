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
        Schema::create('textbooks', function (Blueprint $table) {
            $table->id();
            $table->date("date")->nullable();
            $table->string("image")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('textbook_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('textbook_id')->nullable();
            $table->foreign("textbook_id")->references("id")->on("textbooks")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->longText('description')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('slug')->nullable();
            $table->unique(['textbook_id', 'locale']);
        });

        Schema::create('textbook_media', function (Blueprint $table) {
            $table->id();
            $table->string("file")->nullable();
            $table->tinyInteger("status")->default(0);
            $table->integer("order")->default(0);
            $table->unsignedBigInteger("textbook_id")->nullable();
            $table->foreign("textbook_id")->references("id")->on("textbooks")->noActionOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('textbooks');
    }
};