<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('certificate_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('resident_id')->constrained('residents');
            $table->foreignId('certificate_type_id')->constrained('certificate_types');
            $table->text('purpose')->nullable();
            $table->string('control_number')->unique();
            $table->enum('status', ['pending', 'approved', 'rejected', 'released'])->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
            
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('certificate_requests');
    }
};