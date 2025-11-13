<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrationRequestFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'dob' => $this->faker->date(),
            'role' => $this->faker->randomElement(['Patient','Doctor','Caregiver','Family']),
            'meta' => ['info' => $this->faker->sentence()],
            'approved' => $this->faker->boolean(70),
            'processed_by' => null,
        ];
    }
}
