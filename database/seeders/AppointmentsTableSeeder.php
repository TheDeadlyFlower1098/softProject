<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Employee;
use Carbon\Carbon;

class AppointmentsTableSeeder extends Seeder
{
    public function run()
    {
        $patients = Patient::all();
        $doctors = Employee::where('role', 'Doctor')->get();

        foreach ($patients as $patient) {
            Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctors->random()->id,
                'date' => Carbon::today()->addDays(rand(-5, 10)),
                'notes' => 'Routine check-up',
                'status' => 'scheduled'
            ]);
        }
    }
}
