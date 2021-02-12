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
            [
            	'name' => 'Dane Vincent Painaga',
            	'email' => 'developer@gmail.com',
            	'password' => Hash::make('developer'),
                'municipal_access' => '["BELISON"]',
                'degree_access' => '["Undergraduate"]',
                'user_type' => "User",
                'status' => 'Active',
            ],
            [
                'name' => 'Shanks D. Dragon',
                'email' => 'shanks@gmail.com',
                'password' => Hash::make('shanks'),
                'municipal_access' => '["*"]',
                'degree_access' => '["Undergraduate", "Masters", "Doctorate"]',
                'user_type' => "Admin",
                'status' => 'Active',
            ]
        ]);

    }
}
