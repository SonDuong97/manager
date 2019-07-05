<?php

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
                'id' => 1,
                'username' => 'admin',
                'password' => Hash::make('admin'),
                'email' => 'admin@gmail.com',
                'role_id' => 1,
                'notified' => 0,
                'manager_id' => 0,
            ],
            [
                'id' => 2,
                'username' => 'leader',
                'password' => Hash::make('leader'),
                'email' => 'leader@gmail.com',
                'role_id' => 2,
//                'role' => 'leader',
                'notified' => 1,
                'manager_id' => 0,
            ],
            [
                'id' => 3,
                'username' => 'son-duong',
                'password' => Hash::make('sonduong'),
                'email' => 'duongson29111997@gmail.com',
                'role_id' => 3,
//                'role' => 'staff',
                'notified' => 1,
                'manager_id' => 2,
            ],
        ]);
    }
}
