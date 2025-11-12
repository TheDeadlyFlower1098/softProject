<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Patient;
use Carbon\Carbon;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'patient_id' => Patient::query()->inRandomOrder()->value('id'),
            'amount' => $this->faker->numberBetween(50, 300),
            'description' => $this->faker->sentence(),
            'paid_at' => Carbon::now()->subDays(rand(0,20)),
        ];
    }
}
