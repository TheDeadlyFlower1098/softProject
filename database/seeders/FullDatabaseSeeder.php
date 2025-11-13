<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{
    Role, User, Group, Patient, Employee,
    Roster, Appointment, Prescription,
    MedicineCheck, Payment, RegistrationRequest,
    FamilyMember
};

class FullDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Basic static data
        Role::factory(6)->create();
        Group::factory(4)->create();

        // Generate users
        User::factory(50)->create();

        // Dependent tables
        Employee::factory(50)->create();
        Patient::factory(50)->create();
        Roster::factory(50)->create();
        Appointment::factory(50)->create();
        Prescription::factory(50)->create();
        MedicineCheck::factory(50)->create();
        Payment::factory(50)->create();
        RegistrationRequest::factory(50)->create();
        FamilyMember::factory(50)->create();
    }
}
