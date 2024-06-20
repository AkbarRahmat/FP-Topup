<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'payments';
    protected $fillable = [
        'vendor', 'status', 'product_price', 'seller_cost', 'service_cost', 'total_cost', 'paid_price', 'refund_cost', 'debt_cost'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

