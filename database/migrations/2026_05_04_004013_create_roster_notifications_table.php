<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('roster_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->string('message');
            $table->string('type');        // created / updated / deleted / reminder
            $table->boolean('read')->default(false);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('roster_notifications');
    }
};