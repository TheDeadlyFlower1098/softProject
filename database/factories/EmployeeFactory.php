<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class EmployeeFactory extends Factory
{
    public function definition()
    {
        $roles = ['Doctor','Caregiver','Supervisor'];
        $role = $this->faker->randomElement($roles);

        return [
            'user_id' => User::inRandomOrder()->first()->id ?? null,
            'emp_identifier' => 'EMP' . strtoupper($this->faker->unique()->bothify('####??')),
            'name' => $this->faker->name(),
            'role' => $role,
            'salary' => $this->faker->randomFloat(2, 30000, 90000),
        ];
    }
}
