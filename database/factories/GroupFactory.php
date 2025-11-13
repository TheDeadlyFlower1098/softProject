<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GroupFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => 'Group ' . $this->faker->randomElement(['A','B','C','D','E']),
            'caregiver_id' => null,
        ];
    }
}
