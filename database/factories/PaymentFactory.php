<?php

namespace Database\Factories;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'vendor' => 'Qris',
            'status' => 'pending',
            'total_price' => $this->faker->numberBetween(1000, 100000) // Adding total_price with random integer values
        ];
    }
}
