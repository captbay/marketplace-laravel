<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengusahas', function (Blueprint $table) {
            $table->id();
            // fk to tables users
            $table->foreignId('user_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('phone_number');
            $table->text('address');
            $table->enum('gender', ['MALE', 'FEMALE', 'RATHER NOT SAY']);
            $table->string('profile_picture')->nullable();
            $table->string('background_picture')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengusahas');
    }
};
