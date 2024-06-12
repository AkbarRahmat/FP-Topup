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
        /*// Pastikan ada data di tabel users, products, dan payments
        if (User::count() === 0) {
            User::factory()->count(10)->create();
        }

        if (Product::count() === 0) {
            Product::factory()->count(10)->create();
        }

        if (Payment::count() === 0) {
            Payment::factory()->count(10)->create();
        }

        // Buat transaksi menggunakan data yang ada
        Transaction::factory()->count(10)->create();*/

    }
}
