<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Appointment;

class PrescriptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::query()->inRandomOrder()->value('id'),
            'doctor_id' => Employee::where('role','Doctor')->inRandomOrder()->value('id'),
            'appointment_id' => Appointment::query()->inRandomOrder()->value('id'),
            'content' => $this->faker->sentence(8),
            'notes' => $this->faker->sentence(12),
        ];
    }
}
