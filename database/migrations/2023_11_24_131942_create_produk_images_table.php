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
        Schema::create('produk_images', function (Blueprint $table) {
            $table->id();
            // fk to tables produks
            $table->foreignId('produk_id')->constrained('produks');
            $table->string('original_name');
            $table->string('generated_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produk_images');
    }
};
