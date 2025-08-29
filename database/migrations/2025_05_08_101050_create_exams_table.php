<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->noActionOnDelete();
            $table->integer('duration');
            $table->timestamps();
        });

        Schema::create('exam_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained()->noActionOnDelete();
            $table->string('locale');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('slug')->nullable();
            $table->string('megasubtitle')->nullable();
            $table->unique(['exam_id', 'locale']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('exam_translations');
        Schema::dropIfExists('exams');
    }
};