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
        DB::table('user_credit_logs')->truncate();
        DB::table('user_levels')->truncate();
//        DB::table('commission_levels')->truncate();
        DB::table('user_recharges')->truncate();
        DB::table('user_withdraws')->truncate();
        DB::table('groups')->truncate();

        factory(App\Models\User\Level::class, 5)->create();
        factory(App\Models\User\Withdraw::class, 100)->create();
        factory(App\Models\User\Favourite::class, 100)->create();
        factory(App\Models\User\Address::class, 5)->create();
        factory(App\Models\User\CreditLog::class, 100)->create();
        factory(App\Models\User\Recharge::class, 100)->create();
        factory(App\Models\User\User::class, 100)->create();
        factory(App\Models\User\History::class, 100)->create();
        factory(App\Models\User\Group::class, 50)->create();
    }
}
