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
        Schema::create('free_online_lessons', function (Blueprint $table) {
            $table->id();
            $table->string("icon")->nullable();
            $table->string("url")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('free_online_lesson_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('free_id')->nullable();
            $table->foreign("free_id")->references("id")->on("free_online_lessons")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->unique(['free_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('free_online_lesson_translations');
        Schema::dropIfExists('free_online_lessons');
    }
};