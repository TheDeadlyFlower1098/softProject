<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Roster extends Model
{
    use HasFactory;

    protected $fillable = [
        'date','supervisor_id','doctor_id','caregiver_1','caregiver_2','caregiver_3','caregiver_4'
    ];

    protected $casts = [
        'date' => 'date'
    ];
}
