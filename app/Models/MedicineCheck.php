<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicineCheck extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'caretaker_id',
        'date',
        'morning',
        'afternoon',
        'night',
        'breakfast', 
        'lunch',    
        'dinner',  
    ];

    protected $casts = [
        'date'      => 'date',
        'morning'   => 'string',
        'afternoon' => 'string',
        'night'     => 'string',
        'breakfast' => 'string', 
        'lunch'     => 'string', 
        'dinner'    => 'string', 
    ];
}
