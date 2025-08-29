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
        Schema::create('essay_examples', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->string('video_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->date('date')->nullable();
            $table->integer('type')->default(1);
            $table->timestamps();
        });

        Schema::create('essay_example_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('essay_id')->nullable();
            $table->foreign("essay_id")->references("id")->on("essay_examples")->nullOnDelete();
            $table->string('locale');
            $table->string('title');
            $table->unique(['essay_id', 'locale']);
        });
        
        Schema::create('essay_examples_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('essay_example_id')->nullable();
            $table->foreign("essay_example_id")->references('id')->on("essay_examples")->nullOnDelete();
            $table->string("file_url");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('essay_examples_files');
        Schema::dropIfExists('essay_example_translations');
        Schema::dropIfExists('essay_examples');
    }
};