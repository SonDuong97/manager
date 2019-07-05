<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'name' => 'start_time',
                'value' => '17:00',
            ],
            [
                'name' => 'end_time',
                'value' => '19:00',
            ],

        ]);
    }
}
