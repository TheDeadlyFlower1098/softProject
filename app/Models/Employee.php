<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'emp_identifier',
        'name',
        'role',
        'salary',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
