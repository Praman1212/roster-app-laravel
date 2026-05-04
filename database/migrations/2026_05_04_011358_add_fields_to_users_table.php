<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('users', function (Blueprint $table) {
            // Add color dot for each user
            $table->string('color')->default('#6366f1');
            // Add household link — plain column first, no foreign key yet
            $table->unsignedBigInteger('household_id')->nullable();
            // Now add foreign key AFTER households table is created
            $table->foreign('household_id')
                  ->references('id')
                  ->on('households')
                  ->nullOnDelete();
        });
    }

    public function down() {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['household_id']);
            $table->dropColumn(['color', 'household_id']);
        });
    }
};