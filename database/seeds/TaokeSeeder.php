<?php

use Illuminate\Database\Seeder;

class TaokeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tbk_pids')->truncate();
        DB::table('tbk_orders')->truncate();

        factory(App\Models\Taoke\Pid::class, 5)->create();
        factory(App\Models\Taoke\Order::class, 100)->create();
    }
}
