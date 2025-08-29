<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advertisements', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->string("url")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->integer("order")->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('advertisement_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('advertisement_id')->nullable();
            $table->foreign("advertisement_id")->references("id")->on("advertisements")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->text('description')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('slug')->nullable();
            $table->unique(['advertisement_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advertisements');
    }
};