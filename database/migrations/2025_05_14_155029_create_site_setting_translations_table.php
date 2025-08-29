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
        Schema::create('site_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_setting_id')->nullable();
            $table->foreign("site_setting_id")->references("id")->on("site_settings")->nullOnDelete();
            $table->string('header_offer')->nullable();
            $table->string('locale');
            $table->unique(['site_setting_id', 'locale']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('site_setting_translations');
    }
};
