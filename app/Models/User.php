<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Role;


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

    public function roleName(): ?string
    {
        // Prefer roles table, fall back to string column if you have one
        $fromRelation = optional($this->role)->name;
        $fromColumn   = property_exists($this, 'role') ? $this->role : null;

        return $fromRelation ? strtolower($fromRelation) : ($fromColumn ? strtolower($fromColumn) : null);
    }

    public function hasRole(array $names): bool
    {
        return in_array($this->roleName(), $names, true);
    }

    // if user is a patient:
    public function familyMember()
    {
        return $this->hasOne(\App\Models\FamilyMember::class);
    }

    public function patient()
    {
        return $this->hasOne(\App\Models\Patient::class);
    }
}
