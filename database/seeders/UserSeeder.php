<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $student = User::create([
            'name' => 'Student',
            'email' => 'student@mail.com',
            'password' => Hash::make('password'),
        ]);

        $teacher = User::create([
            'name' => 'Teacher',
            'email' => 'teacher@mail.com',
            'password' => Hash::make('password'),
        ]);
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@mail.com',
            'password' => Hash::make('password'),
        ]);
        $student->roles()->attach(Role::where('name', 'student')->first());
        $teacher->roles()->attach(Role::where('name', 'teacher')->first());
        $admin->roles()->attach(Role::where('name', 'admin')->first());
    }
}
