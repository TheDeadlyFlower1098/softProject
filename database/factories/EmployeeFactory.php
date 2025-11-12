<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class EmployeeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'emp_identifier' => strtoupper($this->faker->bothify('EMP###')),
            'name' => $this->faker->name(),
            'role' => $this->faker->randomElement(['Doctor','Caregiver','Supervisor']),
            'salary' => $this->faker->numberBetween(35000, 90000)
        ];
    }
}
