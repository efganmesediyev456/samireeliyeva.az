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
        Schema::create('topic_file_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_file_id')->nullable();
            $table->foreign("topic_file_id")->references("id")->on("topic_files")->nullOnDelete();
            $table->string('title')->nullable();
            $table->string('locale');
            $table->unique(['topic_file_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topic_file_translations');
    }
};
