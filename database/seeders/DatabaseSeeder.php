<?php

namespace Database\Seeders;

use Database\Seeders\UsersTableSeeder as SeedersUsersTableSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call([
            UsersSeeder::class,
            GameSeeder::class,
            ProductSeeder::class,
            PaymentSeeder::class,
            TransactionSeeder::class,
        ]);

    }

}
