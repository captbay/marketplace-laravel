<?php

namespace Database\Factories;

use App\Models\Konsumen;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Konsumen>
 */
class KonsumenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->create(['role' => 'KONSUMEN'])->id,
            'name' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'gender' => $this->faker->randomElement(['MALE', 'FEMALE', 'RATHER NOT SAY']),
            'profile_picture' => null,
            'background_picture' => null,
        ];
    }

    // public function configure()
    // {
    //     return $this->afterCreating(function (Konsumen $konsumen) {
    //         $user = User::factory()->create(['role' => 'KONSUMEN']);
    //         $konsumen->user_id = $user->id;
    //         $konsumen->save();
    //     });
    // }
}
