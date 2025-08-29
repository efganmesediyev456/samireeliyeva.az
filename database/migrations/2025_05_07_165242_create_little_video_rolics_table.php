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
        Schema::create('little_video_rolics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->string('video_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });

        Schema::create('little_video_rolic_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id')->nullable();
            $table->foreign("video_id")->references("id")->on("little_video_rolics")->nullOnDelete();
            $table->string('locale');
            $table->string('title');
            $table->text('description')->nullable();
            $table->unique(['video_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('little_video_rolic_translations');
        Schema::dropIfExists('little_video_rolics');
    }
};
