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
        Schema::create('blog_new_media', function (Blueprint $table) {
            $table->id();
            $table->string("file")->nullable();
            $table->tinyInteger("status")->default(0);
            $table->integer("order")->default(0);
            $table->unsignedBigInteger("blog_new_id")->nullable();
            $table->foreign("blog_new_id")->references("id")->on("blog_news")->noActionOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_new_media');
    }
};
