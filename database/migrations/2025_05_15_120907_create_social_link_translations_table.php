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
        Schema::create('social_link_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('social_link_id')->constrained()->noActionOnDelete();
            $table->string('locale');
            $table->string('title')->nullable();
            $table->unique(['social_link_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('social_link_translations');
    }
};
