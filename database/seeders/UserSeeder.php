<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'administrator@aplikasi-sekolah.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('administrator');

        User::create([
            'name' => 'Headmaster',
            'email' => 'headmaster@aplikasi-sekolah.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('headmaster');

        User::create([
            'name' => 'Administrative Staff',
            'email' => 'administrative-staff@aplikasi-sekolah.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('administrative-staff');

        User::create([
            'name' => 'Homeroom Teacher',
            'email' => 'homeroom-teacher@aplikasi-sekolah.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('homeroom-teacher');

        User::create([
            'name' => 'Subject Teachers',
            'email' => 'subject-teachers@aplikasi-sekolah.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('subject-teachers');

        User::create([
            'name' => 'Student Guardian',
            'email' => 'student-guardian@aplikasi-sekolah.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('student-guardian');

        User::create([
            'name' => 'Student',
            'email' => 'student@aplikasi-sekolah.com',
            'email_verified_at' => now(),
            'password' => Hash::make('12345678'),
            'created_at' => now(),
            'updated_at' => now()
        ])->assignRole('student');
    }
}
