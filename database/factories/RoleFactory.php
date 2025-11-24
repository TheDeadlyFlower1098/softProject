<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    public function definition()
    {
        static $roles = [
            ['name' => 'Admin', 'access_level' => 100],
            ['name' => 'Supervisor', 'access_level' => 80],
            ['name' => 'Doctor', 'access_level' => 60],
            ['name' => 'Caregiver', 'access_level' => 40],
            ['name' => 'Patient', 'access_level' => 10],
            ['name' => 'Family', 'access_level' => 5],
        ];
        static $i = 0;
        $role = $roles[$i % count($roles)];
        $i++;

        return $role;
    }
}
