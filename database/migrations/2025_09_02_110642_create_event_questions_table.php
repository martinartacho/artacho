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
        Schema::create('event_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->text('question');
            $table->enum('type', ['single', 'multiple', 'text']);
            $table->json('options')->nullable();
            $table->boolean('required')->default(false);
            // $table->boolean('is_template')->default(false);
            // $table->string('template_name')->nullable();
            // $table->text('template_description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('event_questions');
    }
};