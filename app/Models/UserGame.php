<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGame extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'usergames';
    protected $fillable = ['username'];
}
