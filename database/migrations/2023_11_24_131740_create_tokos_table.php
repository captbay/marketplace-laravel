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
        Schema::create('tokos', function (Blueprint $table) {
            $table->id();
            // fk to tables pengushas
            $table->foreignId('pengusaha_id')->constrained('pengusahas');
            $table->string('name');
            $table->string('phone_number');
            $table->text('address');
            $table->text('description');
            $table->string('toko_pp')->nullable();
            $table->string('toko_bg')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tokos');
    }
};
