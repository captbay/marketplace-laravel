<?php

namespace Database\Factories;

use App\Models\Konsumen;
use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penjualan_produk>
 */
class Penjualan_produkFactory extends Factory
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
            // 'jumlah' => $this->faker->numberBetween(1, 10),
            // 'total_harga' => $this->faker->numberBetween(10000, 100000),
        ];
    }
}
