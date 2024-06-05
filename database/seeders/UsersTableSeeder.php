<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'adminuser',
            'email' => 'admin@example.com',
            'password' => 'password', // Mutator di model User akan otomatis meng-hash password
            'phone' => '1234567890',
            'role' => 'admin',
            'remember_token' => Str::random(10),
            'last_login' => now()
        ]);

        User::create([
            'username' => 'buyeruser',
            'email' => 'buyer@example.com',
            'password' => 'password',
            'phone' => '1234567891',
            'role' => 'buyer',
            'remember_token' => Str::random(10),
            'last_login' => now()
        ]);

        User::create([
            'username' => 'selleruser',
            'email' => 'seller@example.com',
            'password' => 'password',
            'phone' => '1234567892',
            'role' => 'seller',
            'remember_token' => Str::random(10),
            'last_login' => now()
        ]);

        User::create([
            'username' => 'usercoy',
            'email' => 'usercoy@example.com',
            'password' => 'password',
            'phone' => '1234567892',
            'role' => 'user',
            'remember_token' => Str::random(10),
            'last_login' => now()
        ]);
    }
}
