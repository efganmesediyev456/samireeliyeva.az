<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("exam_id")->nullable();
            $table->foreign("exam_id")->references("id")->on("exams")->nullOnDelete();
            // $table->integer('position')->default(0);
            // $table->integer('points')->default(1);
            $table->integer('type')->default(1); // For question types (single choice, multiple choice, etc.)
            $table->timestamps();
        });

        Schema::create('exam_question_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("question_id")->nullable();
            $table->foreign('question_id')->references('id')->on("exam_questions")->nullOnDelete();
            $table->string('locale');
            $table->text('question_text')->nullable();
            $table->unique(['question_id', 'locale']);
        });

        Schema::create('exam_question_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("question_id")->nullable();
            $table->foreign('question_id')->references('id')->on("exam_questions")->nullOnDelete();
            $table->boolean('is_correct')->default(false);
            // $table->integer('position')->default(0);
            $table->timestamps();
        });

        Schema::create('exam_question_option_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("option_id")->nullable();
            $table->foreign('option_id')->references('id')->on("exam_question_options")->nullOnDelete();
            $table->string('locale');
            $table->text('option_text');
            $table->unique(['option_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_question_option_translations');
        Schema::dropIfExists('exam_question_options');
        Schema::dropIfExists('exam_question_translations');
        Schema::dropIfExists('exam_questions');
    }
};