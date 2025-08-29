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
       Schema::table('exams', function (Blueprint $table) {
           
            $table->unsignedBigInteger("exam_status_id")->nullable();
            $table->foreign("exam_status_id")->references("id")->on("exam_statuses")->nullOnDelete();
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table("exams", function(Blueprint $table){
            $table->dropForeign(['exam_status_id']);
            $table->dropColumn("exam_status_id");
        });
    }
};
