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
        Schema::create('produks', function (Blueprint $table) {
            $table->id();
            // fk to tables tokos
            $table->foreignId('toko_id')->constrained('tokos');
            $table->string('name');
            $table->text('description');
            $table->integer('price');
            $table->integer('stock');
            $table->string('category'); // sementara ('PATUNG', 'LUKISAN')
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
    }
};
