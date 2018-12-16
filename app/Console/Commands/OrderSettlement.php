<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\Spider\OrderRebate;

class OrderSettlement extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order-settlement';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '结算订单';

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
        $order = db('tbk_orders')
            ->where('status', 2)
            ->whereDate('complete_at', date('Y-m', strtotime('-1 month')))
            ->get()
            ->toArray();

        //改为队列：creditorder  返利
        if (count($order) > 0) {
            foreach ($order as $v) {
                OrderRebate::dispatch($v);
            }
        }
    }
}
