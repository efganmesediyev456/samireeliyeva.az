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
       Schema::create('exam_statuses', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(1);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger("subcategory_id")->nullable();
            
            $table->timestamps();
        });

         Schema::create('exam_status_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exam_status_id');
            $table->string('locale');
            $table->string('title')->nullable();
            $table->foreign('exam_status_id')->references('id')->on('exam_statuses')->onDelete('cascade');
            $table->unique(['exam_status_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_status_translations');
        Schema::dropIfExists('exam_statuses');
    }
};
