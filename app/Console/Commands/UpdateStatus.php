<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

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

        $this->info("\n开始处理");

        db('tbk_coupons')
            ->where('status', 1)
            ->delete();

        db('tbk_coupons')
            ->where('status', 0)
            ->update([
                'status' => 1,
                'updated_at' => now()->toDateTimeString()
            ]);


        DB::select(DB::raw('DELETE n1 FROM tbk_coupons n1, tbk_coupons n2 WHERE n1.id < n2.id AND n1.item_id = n2.item_id AND n1.type = n2.type'));

        db('tbk_kuaiqiang')->where('updated_at','<',now()->format('Y-m-d 00:00:00'))->delete();

        return $this->info("\n数据处理完成");

    }
}
