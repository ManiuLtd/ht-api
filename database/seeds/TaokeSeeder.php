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
        DB::table('tbk_quan')->truncate();
        DB::table('tbk_member_favourites')->truncate();
        DB::table('tbk_member_histories')->truncate();
        DB::table('tbk_categories')->truncate();

        factory(App\Models\Taoke\Category::class, 10)->create();
        factory(App\Models\Taoke\History::class, 30)->create();
        factory(App\Models\Taoke\Favourite::class, 30)->create();
        factory(App\Models\Taoke\Pid::class, 100)->create();
        factory(App\Models\Taoke\Order::class, 100)->create();
        factory(App\Models\Taoke\Quan::class, 100)->create();
    }
}
