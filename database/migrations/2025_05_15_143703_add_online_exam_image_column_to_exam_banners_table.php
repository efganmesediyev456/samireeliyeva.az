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
        Schema::table('exam_banners', function (Blueprint $table) {
            $table->string("exam_online_image")->nullable();
        });

         Schema::table('exam_banner_translations', function (Blueprint $table) {
            $table->string("exam_online_title")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_banners', function (Blueprint $table) {
            //
        });
    }
};
