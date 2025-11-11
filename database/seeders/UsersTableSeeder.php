<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('name','Admin')->first();

        User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'first_name' => 'Admin',
            'last_name' => 'User',
            'password' => Hash::make('password'),
            'role_id' => $adminRole ? $adminRole->id : null,
            'approved' => true
        ]);
    }
}
