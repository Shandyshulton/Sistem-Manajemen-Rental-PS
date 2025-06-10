<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah email sudah ada sebelum insert
        if (!DB::table('staffs')->where('email', 'adminps@gmail.com')->exists()) {
            DB::table('staffs')->insert([
                'name' => 'Admin',
                'email' => 'adminps@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('admin123'),
            ]);
        }

        if (!DB::table('staffs')->where('email', 'operatorps@gmail.com')->exists()) {
            DB::table('staffs')->insert([
                'name' => 'Operator',
                'email' => 'operatorps@gmail.com',
                'role' => 'operator',
                'password' => Hash::make('operator123'),
            ]);
        }
    }
}
