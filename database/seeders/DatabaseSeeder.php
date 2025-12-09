<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

use App\Models\Role;
use App\Models\User;
use App\Models\Patient;
use App\Models\Employee;
use App\Models\Group;
use App\Models\Roster;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\MedicineCheck;
use App\Models\Payment;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1) ROLES (explicit, not random)
        $roles = [
            'Admin'      => Role::create(['name' => 'Admin',      'access_level' => 100]),
            'Supervisor' => Role::create(['name' => 'Supervisor', 'access_level' => 80]),
            'Doctor'     => Role::create(['name' => 'Doctor',     'access_level' => 60]),
            'Caregiver'  => Role::create(['name' => 'Caregiver',  'access_level' => 40]),
            'Patient'    => Role::create(['name' => 'Patient',    'access_level' => 10]),
            'Family'     => Role::create(['name' => 'Family',     'access_level' => 5]),
        ];

        // 2) USERS per role
        $admins = User::factory(1)->create([
            'role_id' => $roles['Admin']->id,
            'email'   => 'admin@example.com',
        ]);

        $supervisorUsers = User::factory(2)->create([
            'role_id' => $roles['Supervisor']->id,
        ]);

        $doctorUsers = User::factory(5)->create([
            'role_id' => $roles['Doctor']->id,
        ]);

        $caregiverUsers = User::factory(8)->create([
            'role_id' => $roles['Caregiver']->id,
        ]);

        $patientUsers = User::factory(25)->create([
            'role_id' => $roles['Patient']->id,
        ]);

        $familyUsers = User::factory(10)->create([
            'role_id' => $roles['Family']->id,
        ]);

        // 3) EMPLOYEES for supervisors / doctors / caregivers
        $supervisorEmployees = $supervisorUsers->map(function (User $u, $i) {
            return Employee::factory()->create([
                'user_id'        => $u->id,
                'emp_identifier' => 'SUP-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name'           => "{$u->first_name} {$u->last_name}",
                'role'           => 'Supervisor',
            ]);
        });

        $doctorEmployees = $doctorUsers->map(function (User $u, $i) {
            return Employee::factory()->create([
                'user_id'        => $u->id,
                'emp_identifier' => 'DOC-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name'           => "{$u->first_name} {$u->last_name}",
                'role'           => 'Doctor',
            ]);
        });

        $caregiverEmployees = $caregiverUsers->map(function (User $u, $i) {
            return Employee::factory()->create([
                'user_id'        => $u->id,
                'emp_identifier' => 'CG-' . str_pad($i + 1, 3, '0', STR_PAD_LEFT),
                'name'           => "{$u->first_name} {$u->last_name}",
                'role'           => 'Caregiver',
            ]);
        });

        // 4) GROUPS – 4 groups, each assigned a caregiver
        $groups = collect();
        for ($i = 1; $i <= 4; $i++) {
            $groups->push(
                Group::factory()->create([
                    'name'         => "Group {$i}",
                    'caregiver_id' => $caregiverEmployees->random()->id, // employee id
                ])
            );
        }

        // 5) PATIENTS – for each patient user
        $patients = collect();
        foreach ($patientUsers as $idx => $u) {
            $patients->push(
                Patient::factory()->create([
                    'user_id'            => $u->id,
                    'patient_identifier' => 'PAT-' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                    'patient_name'       => "{$u->first_name} {$u->last_name}",
                    'group_id'           => $groups->random()->id,
                    'family_code'        => $u->family_code,  // reuse from user
                ])
            );
        }

        // 6) ROSTERS – for a date range around today
        $period = CarbonPeriod::create(
            Carbon::today()->subDays(7),
            Carbon::today()->addDays(7)
        );

        $rosters = collect();
        foreach ($period as $date) {
            $caregiversShuffled = $caregiverEmployees->shuffle()->values();

            $rosters->push(
                Roster::factory()->create([
                    'date'          => $date->toDateString(),
                    'supervisor_id' => $supervisorEmployees->random()->id,
                    'doctor_id'     => $doctorEmployees->random()->id,
                    'caregiver_1'   => optional($caregiversShuffled->get(0))->id,
                    'caregiver_2'   => optional($caregiversShuffled->get(1))->id,
                    'caregiver_3'   => optional($caregiversShuffled->get(2))->id,
                    'caregiver_4'   => optional($caregiversShuffled->get(3))->id,
                ])
            );
        }

        // 7) APPOINTMENTS – a few per day, tied to roster doctor + patients
        $appointments = collect();
        foreach ($rosters as $roster) {
            $patientsToday = $patients->random(rand(3, 6));

            foreach ($patientsToday as $patient) {
                $time = Carbon::parse($roster->date)
                    ->setTime(rand(9, 16), Arr::random([0, 15, 30, 45]));

                $appointments->push(
                    Appointment::factory()->create([
                        'patient_id' => $patient->id,
                        'doctor_id'  => $roster->doctor_id,  // employee id
                        'date'       => $time,
                    ])
                );
            }
        }

        // 8) PRESCRIPTIONS – for some (completed) past appointments
        foreach ($appointments as $appt) {
            if ($appt->date < now() && $appt->status !== 'cancelled') {
                Prescription::factory()->create([
                    'patient_id'     => $appt->patient_id,
                    'doctor_id'      => $appt->doctor_id,
                    'appointment_id' => $appt->id,
                ]);
            }
        }

        // 9) MEDICINE CHECKS – per group patient for last 3 days
        $checkDays = CarbonPeriod::create(
            Carbon::today()->subDays(3),
            Carbon::today()
        );

        foreach ($groups as $group) {
            $groupPatients = $patients->where('group_id', $group->id);

            foreach ($checkDays as $day) {
                foreach ($groupPatients as $patient) {
                    MedicineCheck::factory()->create([
                        'caregiver_id' => $group->caregiver_id,   // employee id
                        'patient_id'   => $patient->id,
                        'date'         => $day->toDateString(),
                    ]);
                }
            }
        }

        // 10) PAYMENTS – 1-3 per patient
        foreach ($patients as $patient) {
            Payment::factory(rand(1, 3))->create([
                'patient_id' => $patient->id,
            ]);
        }

        // (Optional) Registration requests, family members, etc
        // You can use your existing factories freely here, they don't affect relations.
         \App\Models\RegistrationRequest::factory(20)->create();

         
         \Database\Seeders\BackfillMealsOnMedicineChecksSeeder::class;
    }
}
