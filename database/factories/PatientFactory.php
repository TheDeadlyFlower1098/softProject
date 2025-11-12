<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Group;
use Carbon\Carbon;

class PatientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => null,
            'patient_identifier' => strtoupper($this->faker->bothify('P####')),
            'patient_name' => $this->faker->name(),
            'group_id' => Group::query()->inRandomOrder()->value('id') ?? null,
            'admission_date' => Carbon::today()->subDays(rand(1, 300)),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'family_code' => strtoupper($this->faker->lexify('FCODE??')),
        ];
    }
}
