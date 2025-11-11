<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FamilyMember extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','patient_id','relation','family_code'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
