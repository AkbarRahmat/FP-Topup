<?php

namespace Database\Factories;
use App\Models\Category;
use App\Models\Game;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->randomElement(['11 DM', '50 DM', '150 DM', '250 DM', '500 DM', '1000 DM', '1500 DM']),
            'price' => $this->faker->numberBetween(3000, 439000),
            //'category_id' => Category::factory(), // Menggunakan relasi factory untuk Category
            //'game_id' => Game::factory(), // Menggunakan relasi factory untuk Game
            'category_id' => '9c426250-a032-4e9f-9add-66b6260d880b',
            'game_id' => '9c4265f8-9586-40ac-96db-8d8cb5e1d165',
        ];
    }
}