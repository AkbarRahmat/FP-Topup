<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserGame>
 */
class UserGameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'globalid' => $this->faker->unique()->numberBetween(100000001, 999999999),
            'server' => $this->faker->unique()->numberBetween(1000, 9999),
            'username' => $this->faker->unique()->userName(),
        ];
    }
}
