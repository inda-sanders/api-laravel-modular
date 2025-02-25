<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {

        $roles = [
            'superadmin',
            'admin',
            'user'
        ];
        foreach ($roles as $value) :
            $value = Role::create(['name' => $value, 'guard_name' => 'api']);
        endforeach;

        $appList = [
            'public',
            'transactional',
            'finance',
            'thirdParty',
            'bank'
        ];

        foreach ($appList as $key => $value) {
            $super_admin = User::create([
                'name' => 'superadmin',
                'username' => 'superadmin',
                'email' => 'superadmin@admin.com',
                'password' => '$2y$08$LE4H5hSpdxI5Lnfgt/CjzufLr9x33ZvDTOUA46Q4ZwbKCNQTa6/va',
                'client_id' => $key + 1
            ]);
            $user_admin = User::create([
                'name' => 'admin',
                'username' => 'admin',
                'email' => 'admin@admin.com',
                'password' => '$2y$08$LE4H5hSpdxI5Lnfgt/CjzufLr9x33ZvDTOUA46Q4ZwbKCNQTa6/va',
                'client_id' => $key + 1
            ]);
            $user = User::create([
                'name' => 'user',
                'username' => 'user',
                'email' => 'user@admin.com',
                'password' => '$2y$08$LE4H5hSpdxI5Lnfgt/CjzufLr9x33ZvDTOUA46Q4ZwbKCNQTa6/va',
                'client_id' => $key + 1
            ]);
            //    Roles

            // Role::truncate();
            $super_admin->assignRole('superadmin');
            $user_admin->assignRole('admin');
            $user->assignRole('user');

            $super_admin->createToken('value')->accessToken;
            $user_admin->createToken('value')->accessToken;
            $user->createToken('value')->accessToken;
        }
        // User::truncate();

    }
}
