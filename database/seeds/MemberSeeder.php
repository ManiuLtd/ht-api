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
        DB::table ('members')->truncate ();
        DB::table ('member_credit_logs')->truncate ();
        DB::table ('member_levels')->truncate ();
        DB::table ('member_recharges')->truncate ();
        DB::table ('member_withdraws')->truncate ();

        factory (App\Models\Member\MemberLevel::class, 5)->create ();
        factory (App\Models\Member\MemberFavourite::class, 10)->create ();
        factory (App\Models\Member\MemberAddress::class, 5)->create ();
        factory (App\Models\Member\MemberCreditLog::class, 100)->create ();
        factory (App\Models\Member\Member::class, 50)->create ();
        factory (App\Models\Member\MemberHistory::class, 100)->create ();
    }
}
