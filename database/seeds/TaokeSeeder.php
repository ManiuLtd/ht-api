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

        factory(App\Models\Taoke\Pid::class, 5)->create();
    }
}
