<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        DB::table('categories')->insert([
            ['id' => '9c426250-a032-4e9f-9add-66b6260d880b', 'name' => 'Item Game'],
            ['id' => '9c426251-ee5a-49b8-be47-c330345b17a1', 'name' => 'Pulsa'],
        ]);
    }
}

