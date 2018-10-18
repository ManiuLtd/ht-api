<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CouponTool extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coupon-tool {--type=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '处理优惠券';

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
        $type = $this->option('type');
        switch ($type)
        {
            case 1:
                $where['type'] = 1;
                break;
            case 2:
                $where['type'] = 2;
                break;
            case 3:
                $where['type'] = 3;
                break;
            default:
                $where['type'] = 1;
                break;
        }

        DB::table('tbk_coupons')
            ->where('status', 0)
            ->where($where)
            ->update([
                'status' => 1,
                'updated_at' => now()->toDateTimeString()
            ]);

        DB::table('tbk_coupons')
            ->where($where)
            ->where('updated_at', '<', now()->addMinute(-300)->toDateTimeString())
            ->delete();
    }
}
