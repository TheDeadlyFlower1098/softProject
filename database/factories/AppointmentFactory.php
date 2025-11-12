<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use App\Models\Employee;
use Carbon\Carbon;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::query()->inRandomOrder()->value('id'),
            'doctor_id' => Employee::where('role','Doctor')->inRandomOrder()->value('id'),
            'date' => Carbon::now()->addDays(rand(-10,10)),
            'notes' => $this->faker->sentence(),
            'status' => $this->faker->randomElement(['scheduled','completed','cancelled'])
        ];
    }
}
