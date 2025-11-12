<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $supervisorRole = Role::where('name', 'Supervisor')->first();
        $doctorRole = Role::where('name', 'Doctor')->first();
        $caregiverRole = Role::where('name', 'Caregiver')->first();

        // Admin
        User::firstOrCreate(['email' => 'admin@example.com'], [
            'first_name' => 'System',
            'last_name' => 'Admin',
            'password' => Hash::make('password'),
            'role_id' => $adminRole?->id,
            'approved' => true
        ]);

        // Supervisor
        User::firstOrCreate(['email' => 'supervisor@example.com'], [
            'first_name' => 'Main',
            'last_name' => 'Supervisor',
            'password' => Hash::make('password'),
            'role_id' => $supervisorRole?->id,
            'approved' => true
        ]);

        // Doctors
        foreach (['John', 'Amelia', 'Raj'] as $i => $name) {
            User::firstOrCreate(['email' => strtolower($name).'@doctor.com'], [
                'first_name' => $name,
                'last_name' => 'Doctor',
                'password' => Hash::make('password'),
                'role_id' => $doctorRole?->id,
                'approved' => true
            ]);
        }

        // Caregivers
        foreach (['Maya', 'Luke', 'Sophia', 'Ethan'] as $name) {
            User::firstOrCreate(['email' => strtolower($name).'@caregiver.com'], [
                'first_name' => $name,
                'last_name' => 'Caregiver',
                'password' => Hash::make('password'),
                'role_id' => $caregiverRole?->id,
                'approved' => true
            ]);
        }
    }
}
