<?php
namespace Database\Seeders;

use App\Models\Game;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run()
    {
        Game::firstOrCreate(['id' => '9c4265f8-9586-40ac-96db-8d8cb5e1d165', 'name' => 'Mobile Legends', 'code' => 'ml', 'weight_popular' => 1000]);
        Game::firstOrCreate(['id' => '9c4265f8-969e-4972-83e8-77aba72d2b6a', 'name' => 'PUBG Mobile', 'code' => 'pubgm', 'weight_popular' => 700]);
        Game::firstOrCreate(['id' => '9c4265f8-f02f-4589-a4d2-df5ba4e74630', 'name' => 'Free Fire', 'code' => 'ff', 'weight_popular' => 900]);
    }
}
