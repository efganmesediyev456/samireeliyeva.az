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
        Schema::create('important_links', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->string("url")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('important_link_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('important_link_id')->nullable();
            $table->foreign("important_link_id")->references("id")->on("important_links")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['important_link_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('important_link_translations');
        Schema::dropIfExists('important_links');
    }
};