<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesTableSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['name'=>'Admin','access_level'=>100],
            ['name'=>'Supervisor','access_level'=>80],
            ['name'=>'Doctor','access_level'=>60],
            ['name'=>'Caregiver','access_level'=>40],
            ['name'=>'Patient','access_level'=>10],
            ['name'=>'Family','access_level'=>5],
        ];

        foreach ($roles as $r) {
            Role::firstOrCreate(['name'=>$r['name']], $r);
        }
    }
}
