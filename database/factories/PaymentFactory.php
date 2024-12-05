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
            'vendor' => 'Qris',
            'status' => 'pending',
            'reference' => 'TES-12345',
            'product_price' => 10000,
            'seller_cost' => 1000,
            'service_cost' => 1000,
            'total_cost' => 12000,
            'paid_price' => 12000,
            'expired_at' => now()->addHour(1)
        ];
    }
}
