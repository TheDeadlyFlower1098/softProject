<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RosterFactory extends Factory
{
    public function definition()
    {
        return [
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'supervisor_id' => rand(1, 10),
            'doctor_id' => rand(1, 10),
            'caregiver_1' => rand(1, 10),
            'caregiver_2' => rand(1, 10),
            'caregiver_3' => rand(1, 10),
            'caregiver_4' => rand(1, 10),
        ];
    }
}
