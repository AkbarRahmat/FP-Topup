<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::firstOrCreate([
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'password' => 'password', // Mutator di model User akan otomatis meng-hash password
            'phone' => '1234567890',
            'role' => 'admin',
            'status' => 'verified',
            'last_login' => now()
        ]);

        User::factory()->count(2)->create();
    }
}
