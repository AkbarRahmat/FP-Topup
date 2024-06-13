<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject; 


class User extends Authenticatable implements JWTSubject 
{
    use HasFactory, Notifiable, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'phone',
        'otp_verification',
        'role'

    
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Automatically hash the user's password when setting it.
     *
     * @param  string  $value
     * @return void
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
      /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */

    use Notifiable;

    protected $dates = [
        'otp_expires_at',
    ];
    
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Return the user's ID
    }
    public function getJWTCustomClaims()
    {
        return []; // Return an empty array, or add custom claims if needed
    }

    
}

