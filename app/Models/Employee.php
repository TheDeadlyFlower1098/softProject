<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','emp_identifier','name','role','salary'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
