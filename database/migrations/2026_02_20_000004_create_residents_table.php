<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('residents', function (Blueprint $table) {
            $table->id();
            $table->string('resident_code')->unique();
            $table->unsignedBigInteger('household_id')->nullable(); // Just add the column, no foreign key yet
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();
            $table->enum('gender', ['male', 'female']);
            $table->date('birthdate');
            $table->enum('civil_status', ['single', 'married', 'widowed', 'separated']);
            $table->string('nationality')->default('Filipino');
            $table->boolean('voter_status')->default(false);
            $table->string('occupation')->nullable();
            $table->string('contact_number')->nullable();
            $table->string('email')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_pwd')->default(false);
            $table->boolean('is_senior')->default(false);
            $table->enum('status', ['active', 'inactive', 'deceased'])->default('active');
            $table->timestamps();
            
            $table->index(['last_name', 'first_name']);
            
            // REMOVE THIS LINE: $table->foreign('household_id')->references('id')->on('households');
        });
    }

    public function down()
    {
        Schema::dropIfExists('residents');
    }
};