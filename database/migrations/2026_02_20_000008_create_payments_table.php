<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('certificate_request_id')->constrained('certificate_requests');
            $table->string('or_number')->nullable();
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'gcash', 'maya', 'bank']);
            $table->timestamp('payment_date')->useCurrent();
            $table->foreignId('received_by')->constrained('users');
            $table->timestamps();
            
            $table->index('payment_date');
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
};