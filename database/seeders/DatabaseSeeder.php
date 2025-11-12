<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Role, Group, User, Employee, Patient, Appointment,
    Prescription, MedicineCheck, Payment
};

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // First, core setup
        $this->call(RolesTableSeeder::class);
        $this->call(GroupsTableSeeder::class);

        // Generate 50 users
        User::factory(50)->create();

        // Generate 50 employees, patients, etc.
        Employee::factory(50)->create();
        Patient::factory(50)->create();
        Appointment::factory(50)->create();
        Prescription::factory(50)->create();
        MedicineCheck::factory(50)->create();
        Payment::factory(50)->create();
    }
}
