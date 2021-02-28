<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('employees')->insert([
            'employee_code' => 'KRY0001',
            'employee_name' => 'Karyawan 1',
            'employee_phone' => '087665124',
            'employee_address' => 'Jalan 123, Jakarta',
            'employee_date_of_birth' => '2020-09-05',
            'employee_email' => 'employee@gmail.com',
            'employee_password' => Hash::make('test123')
        ]);
    }
}
