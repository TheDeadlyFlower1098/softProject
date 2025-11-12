<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Prescription;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Employee;

class PrescriptionsTableSeeder extends Seeder
{
    public function run()
    {
        $appointments = Appointment::all();

        foreach ($appointments as $a) {
            Prescription::create([
                'patient_id' => $a->patient_id,
                'doctor_id' => $a->doctor_id,
                'appointment_id' => $a->id,
                'content' => 'Take 1 tablet of Vitamin D daily.',
                'notes' => 'Follow up in 2 weeks.'
            ]);
        }
    }
}
