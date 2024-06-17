<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'payments';
    protected $fillable = ['vendor', 'status'];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}

