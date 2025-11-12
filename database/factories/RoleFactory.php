<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition(): array
    {
        $roles = ['Admin','Supervisor','Doctor','Caregiver','Patient','Family'];
        $name = $this->faker->unique()->randomElement($roles);
        return [
            'name' => $name,
            'access_level' => match($name) {
                'Admin' => 100,
                'Supervisor' => 80,
                'Doctor' => 60,
                'Caregiver' => 40,
                'Patient' => 10,
                default => 5
            }
        ];
    }
}
