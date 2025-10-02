<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('event_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('frequency', ['daily', 'weekly', 'monthly', 'yearly', 'anniversary']);
            $table->integer('interval')->default(1); // cada X días/semanas/meses
            $table->enum('generation_scope', ['daily', 'weekly', 'monthly'])->default('weekly');
            $table->integer('generation_offset')->default(1); // ej: semanal → 1 semana vista
            $table->timestamps();
        });

        Schema::create('event_template_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_template_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_question_template_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_template');
        Schema::dropIfExists('event_template_questions');
    }
};
