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
        $game = Game::select('code', 'id')->inRandomOrder()->first();
        if ($game['code'] == 'pubgm') {
            $itemFormat = '%d UC';
        } else {
            $itemFormat = '%d DM';
        }

        $existingNames = Product::pluck('name')->toArray();
        $availableNames = array_diff(generateArrayStringNumber(5, 2000, $itemFormat), $existingNames);

        return [
            'name' => $this->faker->randomElement($availableNames),
            'price' => $this->faker->numberBetween(3000, 439000),
            'category' => 'game',
            'game_id' => $game['id']
        ];
    }
}
