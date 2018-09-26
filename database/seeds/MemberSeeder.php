<?php

use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('members')->truncate();
        DB::table('member_credit_logs')->truncate();
        DB::table('member_levels')->truncate();
        DB::table('member_recharges')->truncate();
        DB::table('member_withdraws')->truncate();

        factory(App\Models\Member\Level::class, 5)->create();
        factory(App\Models\Member\Favourite::class, 10)->create();
        factory(App\Models\Member\Address::class, 5)->create();
        factory(App\Models\Member\CreditLog::class, 100)->create();
        factory(App\Models\Member\Recharge::class, 100)->create();
        factory(App\Models\Member\Member::class, 50)->create();
        factory(App\Models\Member\History::class, 100)->create();
    }
}
