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
        'user_id', 'product_id', 'payment_id', 'usergame_id', 'processed_by', 'processed_proof', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');

    }

    public function payment()
    {
        return $this->belongsTo(Payment::class,'payment_id');
    }

    public function usergame()
    {
        return $this->belongsTo(UserGame::class, 'usergame_id');
    }

    public function game()
    {
        return $this->belongsTo(Game::class, 'product_id', 'id');
    }
}
