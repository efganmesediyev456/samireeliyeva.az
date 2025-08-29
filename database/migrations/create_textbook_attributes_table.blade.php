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
        Schema::create('textbook_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('textbook_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('textbook_attribute_translations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attr_id')->nullable();
            $table->foreign("attr_id")
                ->references("id")
                ->on("textbook_attributes")
                ->nullOnDelete();
            $table->string('locale')->index();
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->unique(['attr_id', 'locale']);
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists('textbook_attributes');
    }
};