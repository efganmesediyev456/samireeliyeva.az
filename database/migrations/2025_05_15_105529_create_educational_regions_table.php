<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    
    public function up(): void
    {
        Schema::create('educational_regions', function (Blueprint $table) {
            $table->id();
            $table->string("image")->nullable();
            $table->string("url")->nullable();
            $table->tinyInteger("status")->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
        
        Schema::create('educational_region_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->foreign("region_id")->references("id")->on("educational_regions")->nullOnDelete();
            $table->string('locale')->index();
            $table->string('title')->nullable();
            $table->unique(['region_id', 'locale']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('educational_region_translations');
        Schema::dropIfExists('educational_regions');
    }
};