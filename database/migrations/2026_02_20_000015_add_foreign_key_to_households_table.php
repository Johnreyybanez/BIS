<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->foreign('household_id')
                  ->references('id')
                  ->on('households')
                  ->onDelete('set null');
        });

        Schema::table('households', function (Blueprint $table) {
            $table->foreign('head_resident_id')
                  ->references('id')
                  ->on('residents')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('residents', function (Blueprint $table) {
            $table->dropForeign(['household_id']);
        });

        Schema::table('households', function (Blueprint $table) {
            $table->dropForeign(['head_resident_id']);
        });
    }
};