<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Support\Str;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        Transaction::factory()->count(20)->create();
    }
}
