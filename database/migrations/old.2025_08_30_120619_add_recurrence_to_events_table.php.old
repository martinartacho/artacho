<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->default('none');
            $table->integer('recurrence_interval')->default(1);
            $table->date('recurrence_end_date')->nullable();
            $table->integer('recurrence_count')->nullable();
        });
    }

    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn([
                'recurrence_type',
                'recurrence_interval',
                'recurrence_end_date',
                'recurrence_count'
            ]);
        });
    }
};