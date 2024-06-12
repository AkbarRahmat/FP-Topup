<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GameSeeder extends Seeder
{
    public function run()
    {
        DB::table('games')->insert([
            ['id' => '9c4265f8-9586-40ac-96db-8d8cb5e1d165', 'name' => 'Mobile Legends', 'weight_popular' => 1000],
            ['id' => '9c4265f8-969e-4972-83e8-77aba72d2b6a', 'name' => 'PUBG Mobile', 'weight_popular' => 700],
            ['id' => '9c4265f8-f02f-4589-a4d2-df5ba4e74630', 'name' => 'Free Fire', 'weight_popular' => 900],
        ]);
    }
}
