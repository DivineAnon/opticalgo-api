<?php

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('owners')->insert([
            'owner_name' => 'Owner 1',
            'owner_email' => 'owner@gmail.com',
            'owner_password' => Hash::make('test123')
        ]);
    }
}
