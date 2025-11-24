<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RegistrationRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'dob',
        'role',
        'meta',
        'approved',
        'processed_by',
        'emergency_contact',
        'relation_to_contact',
    ];


    protected $casts = [
        'meta' => 'array',
        'approved' => 'boolean'
    ];
}
