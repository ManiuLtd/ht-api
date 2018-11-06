<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        DB::table('users')->insert(
            [
                'name' => 'admin',
                'avatar' => 'https://lorempixel.com/100/100/?48011',
                'email' => 'admin@admin.com',
                'password' => bcrypt('123456'),
                'status' => 1,
                'sms' => 1,
                'remember_token' => str_random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'test',
                'avatar' => 'https://lorempixel.com/100/100/?48011',
                'email' => 'test@test.com',
                'password' => bcrypt('123456'),
                'status' => 0,
                'sms' => 0,
                'remember_token' => str_random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
