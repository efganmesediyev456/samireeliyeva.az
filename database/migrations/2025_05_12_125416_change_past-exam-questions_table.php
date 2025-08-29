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
        Schema::table('past_exam_question_translations', function (Blueprint $table) {
             $table->timestamps();
             $table->dropColumn('title');
             $table->dropColumn('subtitle');
        });

        Schema::table('past_exam_question_translations', function (Blueprint $table) {
             $table->string(column: 'title')->nullable();
             $table->string('subtitle')->nullable();
        });

         Schema::table('past_exam_question_item_translations', function (Blueprint $table) {
             $table->timestamps();
             $table->dropColumn('title');
        });

        Schema::table('past_exam_question_item_translations', function (Blueprint $table) {
            $table->string(column: 'title')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
