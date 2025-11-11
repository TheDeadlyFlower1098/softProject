<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name','last_name','email','password',
        'phone','dob','role_id','approved','family_code'
    ];

    protected $hidden = [
        'password','remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob' => 'date',
        'approved' => 'boolean'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // if user is a patient:
    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id');
    }
}
