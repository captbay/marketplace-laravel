<?php

namespace Database\Factories;

use App\Models\Pengusaha;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Toko>
 */
class TokoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pengusaha_id' => Pengusaha::factory()->create()->id,
            'name' => $this->faker->word(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'description' => $this->faker->text(),
            'toko_pp' => null,
            'toko_bg' => null,
        ];
    }
}
