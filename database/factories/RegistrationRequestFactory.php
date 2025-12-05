<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class RegistrationRequestFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name'  => $this->faker->lastName(),
            'email'      => $this->faker->unique()->safeEmail(),
            'password'   => Hash::make('password123'),
            'dob'        => $this->faker->date(),
            'role'       => $this->faker->randomElement(['Patient', 'Doctor', 'Caregiver', 'Family']),
            'meta'       => ['info' => $this->faker->sentence()],
            'approved'   => false,
            'processed_by' => null,
        ];
    }
}
