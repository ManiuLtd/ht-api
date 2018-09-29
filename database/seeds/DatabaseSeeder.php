<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(MemberSeeder::class);
        $this->call(ShopSeeder::class);
        $this->call(BannerSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(LaratrustSeeder::class);
        $this->call(CmsSeeder::class);
    }
}
