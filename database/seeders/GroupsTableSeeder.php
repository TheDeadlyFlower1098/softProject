<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupsTableSeeder extends Seeder
{
    public function run()
    {
        Group::firstOrCreate(['name'=>'Group A']);
        Group::firstOrCreate(['name'=>'Group B']);
        Group::firstOrCreate(['name'=>'Group C']);
        Group::firstOrCreate(['name'=>'Group D']);
    }
}
