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
        Schema::create('interview_preparations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->string('video_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->date('date')->nullable();
            $table->integer('type')->default(1);
            $table->timestamps();
        });

        Schema::create('interview_preparation_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interview_id')->nullable();
            $table->foreign("interview_id")->references("id")->on("interview_preparations")->nullOnDelete();
            $table->string('locale');
            $table->string('title');
            $table->unique(['interview_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_preparation_translations');
        Schema::dropIfExists('interview_preparations');
    }
};