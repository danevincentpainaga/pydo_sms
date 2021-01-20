<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
        	'name' => 'Dane Vincent',
        	'email' => 'developer@gmail.com',
        	'password' => Hash::make('developer'),
            'municipal_access' => '["BELISON", "SIBALOM"]',
            'scholars_access' => '["Master", "Doctorate"]',
            'user_type' => 1,
            'status' => 'Active',
        ]);
    }
}
