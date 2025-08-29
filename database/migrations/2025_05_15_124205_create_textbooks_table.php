<?php

use App\Models\TextbookBanner;
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
        Schema::create('textbook_banners', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->timestamps();
        });

        Schema::create('textbook_banner_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('textbook_banner_id')->nullable();
            $table->foreign("textbook_banner_id")->references("id")->on("textbook_banners")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->unique(['textbook_banner_id', 'locale']);
        });


        TextbookBanner::create([
            "image"=>"hjsj"
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('textbooks');
    }
};
