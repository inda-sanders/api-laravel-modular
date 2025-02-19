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
            $$value = Role::create(['name' => $value]);
        endforeach;
        // User::truncate();
        $super_admin = User::create([
            'name' => 'superadmin',
            'username' => 'superadmin',
            'email' => 'superadmin@admin.com',
            'password' => '$2y$08$LE4H5hSpdxI5Lnfgt/CjzufLr9x33ZvDTOUA46Q4ZwbKCNQTa6/va',
        ]);
        $user_admin = User::create([
            'name' => 'admin',
            'username' => 'admin',
            'email' => 'admin@admin.com',
            'password' => '$2y$08$LE4H5hSpdxI5Lnfgt/CjzufLr9x33ZvDTOUA46Q4ZwbKCNQTa6/va',
        ]);
        $user = User::create([
            'name' => 'user',
            'username' => 'user',
            'email' => 'user@admin.com',
            'password' => '$2y$08$LE4H5hSpdxI5Lnfgt/CjzufLr9x33ZvDTOUA46Q4ZwbKCNQTa6/va',
        ]);
        //    Roles

        // Role::truncate();

        DB::table('model_has_roles')->truncate();
        $super_admin->assignRole('superadmin');
        $user_admin->assignRole('admin');
        $user->assignRole('user');
    }
}
