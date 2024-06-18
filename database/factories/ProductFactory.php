<?php

namespace Database\Factories;
use App\Models\Game;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        $existingNames = Product::pluck('name')->toArray();
        $availableNames = array_diff(generateArrayStringNumber(5, 2000, '%d DM'), $existingNames);

        return [
            'name' => $this->faker->randomElement($availableNames),
            'price' => $this->faker->numberBetween(3000, 439000),
            'category' => 'game',
            'game_id' => '9c4265f8-9586-40ac-96db-8d8cb5e1d165'
        ];
    }
}
