<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk_image>
 */
class Produk_imageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'produk_id' => Produk::all()->random()->id,
            'original_name' => $this->faker->word() . '.PNG',
            'generated_name' => $this->faker->word() . '.PNG',
        ];
    }
}
