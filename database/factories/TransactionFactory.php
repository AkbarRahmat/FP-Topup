<?php

namespace Database\Factories;

use App\Models\Game;
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
        $game = Game::find($product['game_id']);

        // Usergame Data
        if ($game['code'] == 'pubgm') {
            $usergame = UserGame::factory()->create(['username' => null, 'server' => null]);
        } else if($game['code'] == 'epep') {
            $usergame = UserGame::factory()->create(['server' => null]);
        } else {
            $usergame = UserGame::factory()->create();
        }

        // Payment Data
        $payment_data = [
            'status' => 'success',
            'product_price' => $product['price'],
            'seller_cost' => 1000,
            'service_cost' => 1000,
            'total_cost' => 0,
            'paid_price' => 0,
            'refund_cost' => 0,
            'debt_cost' => 0
        ];

        // Calculate
        calculateTransactionTotalCost($payment_data, true);
        calculateTransactionDebtAndRefund($payment_data);

        $payment = Payment::factory()->create($payment_data);

        return [
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $user['id'],
            'product_id' => $product['id'],
            'usergame_id' => $usergame['id'],
            'payment_id' => $payment['id'],
        ];
}
}


