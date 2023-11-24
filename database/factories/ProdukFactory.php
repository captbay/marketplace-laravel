<?php

namespace Database\Factories;

use App\Models\Toko;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'toko_id' => Toko::all()->random()->id,
            'name' => $this->faker->word(),
            'description' => $this->faker->text(),
            'price' => $this->faker->numberBetween(10000, 100000),
            'stock' => $this->faker->numberBetween(1, 10),
            'category' => $this->faker->randomElement(['PATUNG', 'LUKISAN']),
        ];
    }
}
