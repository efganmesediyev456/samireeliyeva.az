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
        Schema::create('critical_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subcategory_id')->constrained()->onDelete('cascade');
            $table->string('video_url')->nullable();
            $table->string('thumbnail')->nullable();
            $table->date('date')->nullable();
            $table->integer('type')->default(1);
            $table->timestamps();
        });

        Schema::create('critical_reading_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('critical_reading_id')->nullable();
            $table->foreign("critical_reading_id")->references("id")->on("critical_readings")->nullOnDelete();
            $table->string('locale');
            $table->string('title');
            $table->unique(['critical_reading_id', 'locale']);
        });
        
        Schema::create('critical_readings_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('critical_reading_id')->nullable();
            $table->foreign("critical_reading_id")->references('id')->on("critical_readings")->nullOnDelete();
            $table->string("file_url");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('critical_readings_files');
        Schema::dropIfExists('critical_reading_translations');
        Schema::dropIfExists('critical_readings');
    }
};