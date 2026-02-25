<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('household_number')->unique();
            $table->unsignedBigInteger('head_resident_id')->nullable(); // Just add the column
            $table->string('purok')->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
            
            // REMOVE THESE LINES FROM HERE
            // $table->foreign('head_resident_id')
            //       ->references('id')
            //       ->on('residents')
            //       ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('households');
    }
};