<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Patient;
use Carbon\Carbon;

class MedicineCheckFactory extends Factory
{
    public function definition(): array
    {
        return [
            'caregiver_id' => User::whereHas('role', fn($q) => $q->where('name','Caregiver'))->inRandomOrder()->value('id'),
            'patient_id' => Patient::query()->inRandomOrder()->value('id'),
            'date' => Carbon::today()->subDays(rand(0,5)),
            'morning' => $this->faker->boolean(),
            'afternoon' => $this->faker->boolean(),
            'night' => $this->faker->boolean(),
        ];
    }
}
