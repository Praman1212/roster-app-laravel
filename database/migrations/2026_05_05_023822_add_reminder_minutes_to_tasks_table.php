<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('tasks', function (Blueprint $table) {
            // add reminder_minutes column to tasks table
            $table->integer('reminder_minutes')->nullable()->after('start_time');
        });
    }

    public function down() {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('reminder_minutes');
        });
    }
};