<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    //Get the path the user should be redirected to when they are not authenticated.
    protected function redirectTo(Request $request): ?string
    {
        return $request->expectsJson() ? null : route('login');

    }

        public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function roleName(): ?string
    {
        return optional($this->role)->name;
    }

    public function hasRole(array $names): bool
    {
        return in_array($this->roleName(), $names, true);
    }
}
