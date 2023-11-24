<?php

namespace Database\Factories;

use App\Models\Konsumen;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fav_product>
 */
class Fav_productFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'konsumen_id' => Konsumen::all()->random()->id,
            'produk_id' => Produk::all()->random()->id,
        ];
    }
}
