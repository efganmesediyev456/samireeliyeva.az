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
        Schema::create('user_exams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->nullOnDelete();

            $table->unsignedBigInteger("exam_id")->nullable();
            $table->foreign("exam_id")->references("id")->on(table: "exams")->nullOnDelete();

            $table->unsignedBigInteger("exam_question_id")->nullable();
            $table->foreign("exam_question_id")->references("id")->on(table: "exam_questions")->nullOnDelete();

            $table->unsignedBigInteger("answer_id")->nullable();
            $table->foreign("answer_id")->references("id")->on(table: "exam_question_options")->nullOnDelete();

            $table->longText("answer")->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_exams');
    }
};
