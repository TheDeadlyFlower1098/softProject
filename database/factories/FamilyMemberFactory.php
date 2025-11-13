<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use App\Models\Patient;

class FamilyMemberFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? null,
            'patient_id' => Patient::inRandomOrder()->first()->id ?? 1,
            'relation' => $this->faker->randomElement(['Son','Daughter','Brother','Sister','Spouse']),
            'family_code' => strtoupper($this->faker->bothify('FCODE###')),
        ];
    }
}
