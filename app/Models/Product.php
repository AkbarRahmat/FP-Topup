<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = ['name','price', 'category_id', 'game_id'];

    public function game()
    {
        return $this->belongsTo(Game::class);
    }
    
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

