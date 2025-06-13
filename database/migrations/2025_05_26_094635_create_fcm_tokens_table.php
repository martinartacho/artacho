<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('fcm_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('token');
            $table->string('device_type')->nullable();
            $table->string('device_name')->nullable();
            $table->timestamp('last_used_at')->nullable();
            $table->boolean('is_valid')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'token']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('fcm_tokens');
    }
};
