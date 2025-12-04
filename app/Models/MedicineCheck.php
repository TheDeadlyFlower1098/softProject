<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicineCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'caregiver_id','patient_id','date','morning','afternoon','night'
    ];

    protected $casts = [
        'date' => 'datetime',
        'morning' => 'boolean',
        'afternoon' => 'boolean',
        'night' => 'boolean'
    ];
}
