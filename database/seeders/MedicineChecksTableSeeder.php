<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\MedicineCheck;
use App\Models\Patient;
use App\Models\User;
use Carbon\Carbon;

class MedicineChecksTableSeeder extends Seeder
{
    public function run()
    {
        $caregivers = User::whereHas('role', fn($q) => $q->where('name', 'Caregiver'))->get();
        $patients = Patient::all();

        foreach ($patients as $p) {
            MedicineCheck::create([
                'caregiver_id' => $caregivers->random()->id,
                'patient_id' => $p->id,
                'date' => Carbon::today()->subDays(rand(0, 3)),
                'morning' => (bool)rand(0, 1),
                'afternoon' => (bool)rand(0, 1),
                'night' => (bool)rand(0, 1),
            ]);
        }
    }
}
