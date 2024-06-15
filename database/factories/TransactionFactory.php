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
            'user_id' => User::factory()->create()->id,
            'product_id' => Product::factory()->create()->id,
            'payment_id' => Payment::factory()->create()->id,
            'usergame_id' => $this->faker->numerify('#########'), // minimal 9 digit
            'usergame_server' => $this->faker->numberBetween(1, 99999),
            'usergame_name' => $this->faker->userName

        ];
}
}


