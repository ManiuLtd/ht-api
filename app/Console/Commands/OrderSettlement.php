<?php

namespace App\Console\Commands;

use App\Events\CreditOrderFriend;
use App\Events\CreditOrder;
use Illuminate\Console\Command;

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
            ->where('status',2)
            ->whereDate('complete_at',date('Y-m'))
            ->get()
            ->toArray();
        if (count($order) > 0){
            foreach ($order as $v){
                event(new CreditOrderFriend([
                    'user_id' => $v->user_id
                ],1));
            }
        }
        $order_refund = db('tbk_orders')
            ->where('status',5)
            ->whereDate('complete_at',date('Y-m'))
            ->get()
            ->toArray();
        if (count($order_refund) > 0){
            foreach ($order as $v){
                event(new CreditOrder([
                    'user_id' => $v->user_id
                ]));
            }
        }


    }
}
