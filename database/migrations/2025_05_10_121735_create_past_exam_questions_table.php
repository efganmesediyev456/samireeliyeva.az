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
        Schema::create('past_exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->foreign("subcategory_id")->references("id")->on("subcategories")->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('past_exam_question_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_id')->nullable();
            $table->foreign("exam_id")->references("id")->on("past_exam_questions")->nullOnDelete();
            $table->string('locale');
            $table->string('title');
            $table->string('subtitle');
            $table->unique(['exam_id', 'locale']);
        });

        Schema::create('past_exam_question_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('past_exam_question_id')->nullable();
            $table->foreign("past_exam_question_id")->references("id")->on("past_exam_questions")->nullOnDelete();
            $table->string('file');
            $table->timestamps();
        });

        Schema::create('past_exam_question_item_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->foreign("item_id")->references("id")->on("past_exam_question_items")->nullOnDelete();
            $table->string('title');
            $table->string('locale');
            $table->unique(['item_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('past_exam_question_item_translations');
        Schema::dropIfExists('past_exam_question_items');
        Schema::dropIfExists('past_exam_question_translations');
        Schema::dropIfExists('past_exam_questions');
    }
};
