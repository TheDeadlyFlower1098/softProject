<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;

class PaymentFactory extends Factory
{
    public function definition()
    {
        return [
            'patient_id' => Patient::inRandomOrder()->first()->id ?? 1,
            'amount' => $this->faker->randomFloat(2, 50, 500),
            'description' => $this->faker->sentence(),
            'paid_at' => $this->faker->dateTimeBetween('-2 months','now'),
        ];
    }
}
