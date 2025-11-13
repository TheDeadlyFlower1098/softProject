<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Group;
use App\Models\User;

class PatientFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? null,
            'patient_identifier' => 'P' . strtoupper($this->faker->unique()->bothify('#####??')),
            'patient_name' => $this->faker->name(),
            'group_id' => Group::inRandomOrder()->first()->id ?? null,
            'admission_date' => $this->faker->dateTimeBetween('-1 year','now'),
            'emergency_contact_name' => $this->faker->name(),
            'emergency_contact_phone' => $this->faker->phoneNumber(),
            'family_code' => strtoupper($this->faker->bothify('FCODE###')),
        ];
    }
}
