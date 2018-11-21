<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update_status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '更新优惠券状态';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {


        db('tbk_coupons')
            ->where('status', 1)
            ->delete();

        db('tbk_coupons')
            ->where('status', 0)
            ->update([
                'status' => 1,
                'updated_at' => now()->toDateTimeString()
            ]);
    }
}
