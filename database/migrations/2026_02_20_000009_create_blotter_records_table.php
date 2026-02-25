<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('blotter_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('complainant_id')->constrained('residents');
            $table->foreignId('respondent_id')->constrained('residents');
            $table->datetime('incident_date');
            $table->string('incident_location');
            $table->text('description');
            $table->enum('status', ['ongoing', 'settled', 'filed', 'dismissed'])->default('ongoing');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index('status');
            $table->index('incident_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('blotter_records');
    }
};