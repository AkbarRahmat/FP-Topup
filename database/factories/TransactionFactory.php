<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
{
   
        return [
            'id' => $this->faker->uuid,
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => User::factory(), // Menggunakan relasi factory untuk User
            'product_id' => Product::factory(), // Menggunakan relasi factory untuk Product
            'payment_id' => Payment::factory()->create()->id, // Anda dapat menggunakan Payment::factory() atau ID Payment yang ada
        ];
}
}


