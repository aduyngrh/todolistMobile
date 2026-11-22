<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStartAndEndTimeFromTodosTable extends Migration
{
    public function up(): void
    {
        Schema::table('todos', function (Blueprint $table): void {
            $table->dropColumn(['start_time', 'end_time']);
        });
    }

    public function down(): void
    {
        Schema::table('todos', function (Blueprint $table): void {
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
        });
    }
}
