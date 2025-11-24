<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Models\Employee;

class AppointmentFactory extends Factory
{
    public function definition()
    {
        return [
            'patient_id' => Patient::inRandomOrder()->first()->id ?? 1,
            'doctor_id' => Employee::where('role', 'Doctor')->inRandomOrder()->first()->id ?? null,
            'date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'notes' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['scheduled','completed','cancelled']),
        ];
    }
}
