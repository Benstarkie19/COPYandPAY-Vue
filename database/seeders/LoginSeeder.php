<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'ben',
            'email' => 'ben@gmail.com',
            'password' => Hash::make('12345678')
        ]);
    }
}
