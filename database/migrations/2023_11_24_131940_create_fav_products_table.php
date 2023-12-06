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
        Schema::create('fav_products', function (Blueprint $table) {
            $table->id();
            // fk to tables konsumens
            $table->foreignId('konsumen_id')->constrained('konsumens')->onUpdate('cascade')->onDelete('cascade');
            // fk to tables produks
            $table->foreignId('produk_id')->constrained('produks')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fav_products');
    }
};
