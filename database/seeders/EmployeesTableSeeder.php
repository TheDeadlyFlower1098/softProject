<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;

class EmployeesTableSeeder extends Seeder
{
    public function run()
    {
        $doctorUsers = User::whereHas('role', fn($q) => $q->where('name', 'Doctor'))->get();
        $caregivers = User::whereHas('role', fn($q) => $q->where('name', 'Caregiver'))->get();

        foreach ($doctorUsers as $u) {
            Employee::firstOrCreate(['user_id' => $u->id], [
                'emp_identifier' => 'DOC' . str_pad($u->id, 3, '0', STR_PAD_LEFT),
                'name' => $u->first_name . ' ' . $u->last_name,
                'role' => 'Doctor',
                'salary' => 75000,
            ]);
        }

        foreach ($caregivers as $u) {
            Employee::firstOrCreate(['user_id' => $u->id], [
                'emp_identifier' => 'CG' . str_pad($u->id, 3, '0', STR_PAD_LEFT),
                'name' => $u->first_name . ' ' . $u->last_name,
                'role' => 'Caregiver',
                'salary' => 42000,
            ]);
        }
    }
}
