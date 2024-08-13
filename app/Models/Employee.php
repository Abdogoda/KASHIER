<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Authenticatable{

    use HasFactory, HasApiTokens, Notifiable;

    protected $fillable = ['name', 'email', 'phone', 'password', 'status', 'role_id'];
    protected $hidden = ['password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function role(){
        return $this->belongsTo(Role::class, 'role_id');
    }
}