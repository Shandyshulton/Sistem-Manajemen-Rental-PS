<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        DB::table('employees')->insert([
            'id_employee' => 'E001',
            'name' => 'Admin',
            'email' => 'adminps@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }
}
