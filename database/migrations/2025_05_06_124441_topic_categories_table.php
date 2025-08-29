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
        Schema::create('topic_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id');
            $table->foreign('topic_id')->references('id')->on('topics')->cascadeOnDelete();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('topic_category_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_category_id');
            $table->foreign('topic_category_id')->references('id')->on('topic_categories')->cascadeOnDelete();
            $table->string('locale')->index();
            $table->string('title');
            $table->unique(['topic_category_id', 'locale']);
        });


        Schema::create('topic_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_category_id');
            $table->foreign('topic_category_id')->references('id')->on('topic_categories')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name');
            $table->string('file_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
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
