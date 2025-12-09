<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'phone',
        'dob',
        'approved',
        'family_code',
    ];

    /**
     * The attributes that should be hidden for arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'dob'               => 'date',
        'approved'          => 'boolean',
    ];

    /**
     * Relationship: each user belongs to one role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function roleName(): ?string
    {
        // Prefer roles table, fall back to a string column if you ever had one
        $fromRelation = optional($this->role)->name;
        $fromColumn   = property_exists($this, 'role')
            ? $this->role
            : null;

        return $fromRelation
            ? strtolower($fromRelation)
            : ($fromColumn ? strtolower($fromColumn) : null);
    }

    /**
     * Helper: check if user has any of the given roles.
     *
     * Example: $user->hasRole(['admin', 'supervisor'])
     */
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

    public function role_page_role()
    {
        return $this->belongsTo(\App\Models\Role::class);
    }

    // Convenience: $user->roleName()
    public function role_page_roleName(): ?string
    {
        return optional($this->role)->name;
    }

    // Convenience: $user->accessLevel()
    public function accessLevel(): int
    {
        return optional($this->role)->access_level ?? 0;
    }

}
