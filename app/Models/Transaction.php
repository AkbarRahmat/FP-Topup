<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'transactions';
    protected $fillable = [
        'user_id', 'product_id', 'payment_id', 'usergame_id', 'price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);

    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
