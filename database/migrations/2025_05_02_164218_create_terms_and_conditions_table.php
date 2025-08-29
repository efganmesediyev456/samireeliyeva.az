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
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        Schema::create('terms_and_condition_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('terms_id')->nullable();
            $table->foreign("terms_id")->references("id")->on("terms_and_conditions")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->text('description')->nullable();
            $table->unique(['terms_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions');
    }
};
