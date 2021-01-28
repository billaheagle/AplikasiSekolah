<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create(['name' => 'administrator', 'guard_name' => 'web']);
        Role::create(['name' => 'headmaster', 'guard_name' => 'web']);
        Role::create(['name' => 'administrative-staff', 'guard_name' => 'web']);
        Role::create(['name' => 'homeroom-teacher', 'guard_name' => 'web']);
        Role::create(['name' => 'subject-teachers', 'guard_name' => 'web']);
        Role::create(['name' => 'student-guardian', 'guard_name' => 'web']);
        Role::create(['name' => 'student', 'guard_name' => 'web']);
    }
}
