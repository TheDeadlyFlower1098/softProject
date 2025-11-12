<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $roles = Role::all();
        $roleId = $roles->isNotEmpty() ? $roles->random()->id : null;
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
            'phone' => $this->faker->phoneNumber,
            'dob' => $this->faker->date(),
            'role_id' => $roleId,
            'approved' => true,
            'family_code' => strtoupper($this->faker->lexify('FCODE??')),
        ];
    }
}
