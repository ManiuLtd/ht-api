<?php

use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notifications')->truncate();
        DB::table('feedbacks')->truncate();

        factory(App\Models\System\Notification::class, 30)->create();
        factory(App\Models\System\Feedback::class, 30)->create();
    }
}
