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
       Schema::create('exam_categories', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
        });

         Schema::create('exam_category_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_category_id');
            $table->string('locale');
            $table->string('title');
            $table->foreign('exam_category_id')->references('id')->on('exam_categories')->onDelete('cascade');
            $table->unique(['exam_category_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_categories');
    }
};
