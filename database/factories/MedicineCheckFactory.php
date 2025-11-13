<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Patient;

class MedicineCheckFactory extends Factory
{
    public function definition()
    {
        return [
            'caregiver_id' => User::inRandomOrder()->first()->id ?? 1,
            'patient_id' => Patient::inRandomOrder()->first()->id ?? 1,
            'date' => $this->faker->dateTimeBetween('-10 days','now'),
            'morning' => $this->faker->boolean(),
            'afternoon' => $this->faker->boolean(),
            'night' => $this->faker->boolean(),
        ];
    }
}
