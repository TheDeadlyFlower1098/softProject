<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','patient_identifier','patient_name','group_id','admission_date',
        'emergency_contact_name','emergency_contact_phone','family_code'
    ];

    protected $casts = [
        'admission_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
