<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificate_types', function (Blueprint $table) {
            $table->id();
            $table->string('certificate_name');
            $table->text('description')->nullable();
            $table->decimal('fee', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_types');
    }
};