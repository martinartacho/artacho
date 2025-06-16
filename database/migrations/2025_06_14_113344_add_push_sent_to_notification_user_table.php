<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('notification_user', function (Blueprint $table) {
            $table->boolean('push_sent')->default(false)->after('read_at');
        });
    }

    public function down(): void {
        Schema::table('notification_user', function (Blueprint $table) {
            $table->dropColumn('push_sent');
        });
    }
};
