<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var
     */
    protected $results;
    /**
     * @var
     */
    protected $type;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($results, $type)
    {
        $this->type = $type;
        $this->results = $results;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->type) {
            case 'taobao':
                $this->saveTBKOrder($this->results);
                break;
            case 'jingdong':
                $this->saveJDOrder($this->results);
                break;
            case 'pinduoduo':
                $this->savePDDOrder($this->results);
                break;
            default:
                break;

        }
    }

    /**
     * 淘宝客订单.
     * @param $results
     */
    protected function saveTBKOrder($results)
    {
        foreach ($results as $result) {
            $item = [
                'ordernum' => $result->trade_id,
                'title' => $result->item_title,
                'itemid' => $result->num_iid,
                'count' => $result->item_num,
                'price' => $result->price,
                'final_price' => $result->pay_price,
                'commission_rate' => $result->total_commission_rate,
                'commission_amount' => $result->total_commission_fee,
                'pid' => $result->adzone_id,
                'status' => $this->getStatus($result->tk_status),
                'type' => 1,
                'complete_at' => $result->earning_time ?? null,
            ];
            db('tbk_orders')->updateOrInsert([
                'ordernum' => $item['ordernum'],
                'type' => 1,
            ], $item);
            $item = [];
        }
    }

    /**
     * 淘客订单状态
     * @param $status
     * @return int
     */
    protected function getStatus($status)
    {
        switch ($status) {
            case 3:
                return 2;
                break;
            case 12:
                return 1;
                break;
            case 13:
                return 3;
                break;
            case 14:
                return 1;
                break;
            default:
                break;
        }
    }

    protected function savePDDOrder($results)
    {
        foreach ($results as $result) {
            $item = [
                'ordernum' => $result->order_sn,
                'title' => $result->goods_name,
                'itemid' => $result->goods_id,
                'count' => $result->goods_quantity,
                'price' => $result->goods_price,
                'final_price' => $result->order_amount,
                'commission_rate' => $result->promotion_rate / 10,
                'commission_amount' => $result->promotion_amount / 100,
                'pid' => $result->adzone_id,
                'status' => $this->getPDDStatus($result->order_status),
                'type' => 3,
                'complete_at' => $result->earning_time ?? null,
            ];
            db('tbk_orders')->updateOrInsert([
                'ordernum' => $item['ordernum'],
                'type' => 3,
            ], $item);
            $item = [];
        }
    }

    /**
     * 拼多多状态
     * @param $status
     * @return int
     */
    protected function getPDDStatus($status)
    {
        switch ($status) {
            case -1: //未支付
                return -1;
                break;
            case 0:
                return 1;
                break;
            case 1:
                return 1;
                break;
            case 2:
                return 1;
                break;
            case 3:
                return 1;
                break;
            case 4:
                return 3;
                break;
            case 5:
                return 2;
                break;
            case 8:
                return 3;
                break;
            case 10:
                return 3;
                break;
            default:
                break;
        }
    }
}
