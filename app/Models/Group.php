<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name','caregiver_id'];

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }
}
