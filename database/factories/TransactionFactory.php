<?php

namespace Database\Factories;

use App\Models\Transaction;
use App\Models\User;
use App\Models\Product;
use App\Models\Payment;
use App\Models\UserGame;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $usergame = UserGame::factory()->create();
        $payment = Payment::factory()->create([
            'product_price' => $product['price'],
            'seller_cost' => 1000,
            'service_cost' => 1000,
            'total_cost' => $product['price'] + 2000,
            'paid_price' => $product['price'] + 2000
        ]);

        return [
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $user->id,
            'product_id' => $product->id,
            'usergame_id' => $usergame->id,
            'payment_id' => $payment->id,
        ];
}
}


