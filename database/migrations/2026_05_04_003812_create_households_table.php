<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        // Create households table FIRST
        // because users will link to it
        Schema::create('households', function (Blueprint $table) {
            $table->id();
            $table->string('name');                   // e.g. "The Smiths"
            $table->string('invite_code')->unique();  // e.g. "ABCD1234"
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('households');
    }
};