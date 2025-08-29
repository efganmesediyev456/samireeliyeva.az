<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  
    public function up(): void
{
    Schema::create('sub_category_packets', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('subcategory_id');
        $table->integer('duration_months');
        $table->boolean('active')->default(true);
        $table->timestamps();

        $table->foreign('subcategory_id')->references('id')->on('subcategories')->onDelete('cascade');
    });

    Schema::create('sub_category_packet_translations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('packet_id');
        $table->string('locale');
        $table->string('title')->nullable();
        $table->timestamps();

        $table->foreign('packet_id')->references('id')->on('sub_category_packets')->onDelete('cascade');
        $table->unique(['packet_id', 'locale']);
    });

    Schema::create('sub_category_packet_items', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('packet_id');
        $table->string('icon')->nullable();
        $table->decimal('price', 10, 2);
        $table->decimal('discount_price', 10, 2)->nullable();
        $table->timestamps();

        $table->foreign('packet_id')->references('id')->on('sub_category_packets')->onDelete('cascade');
    });

    Schema::create('sub_category_packet_item_translations', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('item_id');
        $table->string('locale');
        $table->string('title')->nullable();
        $table->string('subtitle')->nullable();
        $table->timestamps();

        $table->foreign('item_id')->references('id')->on('sub_category_packet_items')->onDelete('cascade');
        $table->unique(['item_id', 'locale']);
    });
}


    public function down(): void
    {
        Schema::dropIfExists('sub_category_packet_translations');
        Schema::dropIfExists('sub_category_packets');
    }
};