<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Appointment;

class PrescriptionFactory extends Factory
{
    public function definition()
    {
        return [
            'patient_id' => Patient::inRandomOrder()->first()->id ?? 1,
            'doctor_id' => Employee::where('role', 'Doctor')->inRandomOrder()->first()->id ?? 1,
            'appointment_id' => Appointment::inRandomOrder()->first()->id ?? null,
            'content' => $this->faker->sentence(8),
            'notes' => $this->faker->sentence(),
        ];
    }
}
