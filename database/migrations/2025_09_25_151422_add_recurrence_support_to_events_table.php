<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Ampliamos recurrencias
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly', 'yearly', 'anniversary'])
                  ->default('none')
                  ->change();

        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('recurrence_type', ['none', 'daily', 'weekly', 'monthly', 'yearly'])
                  ->default('none')
                  ->change();

            $table->dropConstrainedForeignId('parent_id');
            $table->dropConstrainedForeignId('event_template_id');
        });
    }
};
