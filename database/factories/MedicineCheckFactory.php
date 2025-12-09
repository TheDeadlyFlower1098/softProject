<?php

namespace Database\Factories;

use App\Models\MedicineCheck;
use App\Models\Patient;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class MedicineCheckFactory extends Factory
{
    protected $model = MedicineCheck::class;

    public function definition(): array
    {
        $status = [null, 'taken', 'missed'];

        // pick meds first
        $morning   = $this->faker->randomElement($status);
        $afternoon = $this->faker->randomElement($status);
        $night     = $this->faker->randomElement($status);

        // meals roughly mirror meds (so they look realistic)
        $breakfast = $morning   ?? $this->faker->randomElement($status);
        $lunch     = $afternoon ?? $this->faker->randomElement($status);
        $dinner    = $night     ?? $this->faker->randomElement($status);

        return [
            'patient_id'   => Patient::inRandomOrder()->value('id') ?? Patient::factory(),
            'caregiver_id' => Employee::inRandomOrder()->value('id') ?? null,
            'date'         => $this->faker->dateTimeBetween('-7 days', 'now'),

            'morning'      => $morning,
            'afternoon'    => $afternoon,
            'night'        => $night,

            'breakfast'    => $breakfast,
            'lunch'        => $lunch,
            'dinner'       => $dinner,
        ];
    }
}
