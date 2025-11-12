<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Patient;
use App\Models\Group;
use Carbon\Carbon;

class PatientsTableSeeder extends Seeder
{
    public function run()
    {
        $groups = Group::all();

        foreach (range(1, 10) as $i) {
            $group = $groups->random();
            Patient::firstOrCreate(['patient_identifier' => 'P' . str_pad($i, 4, '0', STR_PAD_LEFT)], [
                'patient_name' => 'Patient ' . $i,
                'group_id' => $group->id,
                'admission_date' => Carbon::today()->subDays(rand(10, 100)),
                'emergency_contact_name' => 'Contact ' . $i,
                'emergency_contact_phone' => '555-010' . $i,
                'family_code' => 'FCODE' . rand(100, 999),
            ]);
        }
    }
}
