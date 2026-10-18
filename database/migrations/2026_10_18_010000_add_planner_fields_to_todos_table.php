<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('due_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->dateTime('reminder_at')->nullable()->after('end_time');
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->dropColumn(['start_time', 'end_time', 'reminder_at']);
        });
    }
};