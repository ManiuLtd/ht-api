<?php

namespace App\Jobs;

use App\Models\Taoke\Pid;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SaveOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 1;
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
            $pid = Pid::query()->where('taobao','like', '%'.$result->adzone_id)->first();
            if ($pid) {
                $user = User::query()->find($pid->user_id);
                $group_id = $user->group_id;
                $oldgroup_id = $user->oldgroup_id;
                $user_id = $user->id;
            } else {
                $group_id = null;
                $oldgroup_id = null;
                $user_id = null;
            }
            $item = [
                'user_id'           => $user_id,
                'group_id'          => $group_id,
                'oldgroup_id'       => $oldgroup_id,
                'ordernum'          => $result->trade_id,
                'title'             => $result->item_title,
                'itemid'            => $result->num_iid,
                'count'             => $result->item_num,
                'price'             => $result->price,
                'final_price'       => $result->alipay_total_price,
                'commission_rate'   => $result->total_commission_rate,
                'commission_amount' => $result->pub_share_pre_fee,
                'pid'               => $result->adzone_id,
                'status'            => $this->getStatus($result->tk_status),
                'type'              => 1,
                'complete_at'       => $result->earning_time ?? null,
                'created_at'       => $result->create_time,
                'updated_at'       => now()->toDateTimeString(),
            ];
            db('tbk_orders')->updateOrInsert([
                'ordernum' => $item['ordernum'],
                'itemid' => $item['itemid'],
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
            $pid = Pid::query()->where('pinduoduo', $result->p_id)->first();
            if ($pid) {
                $user = User::query()->find($pid->user_id);
                $user_id = $user->id;
                $group_id = $user->group_id;
                $oldgroup_id = $user->oldgroup_id;
            } else {
                $user_id = null;
                $group_id = null;
                $oldgroup_id = null;
            }
            $item = [
                'user_id'         => $user_id,
                'group_id'          => $group_id,
                'oldgroup_id'       => $oldgroup_id,
                'ordernum'          => $result->order_sn,
                'title'             => $result->goods_name,
                'itemid'            => $result->goods_id,
                'count'             => $result->goods_quantity,
                'price'             => $result->goods_price / 100,
                'final_price'       => $result->order_amount / 100,
                'commission_rate'   => $result->promotion_rate / 10,
                'commission_amount' => $result->promotion_amount / 100,
                'pid'               => $result->p_id,
                'status'            => $this->getPDDStatus($result->order_status),
                'type'              => 3,
                'pic_url'           => $result->goods_thumbnail_url,
                'complete_at'       => $result->order_modify_at ? date('Y-m-d H:i:s', $result->order_create_time) : null,
                'created_at'       => $result->order_create_time ? date('Y-m-d H:i:s', $result->order_create_time) : null,
                'updated_at'       => now()->toDateTimeString(),
            ];
            db('tbk_orders')->updateOrInsert([
                'ordernum' => $item['ordernum'],
                'itemid' => $item['itemid'],
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
                return 3;
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

    protected function saveJDOrder($results)
    {
        foreach ($results as $result) {
            $p_id = $result->unionId.'_0_'.$result->skuList[0]->spId;

            $pid = Pid::query()->where('jingdong', $p_id)->first();
            if ($pid) {
                $user = User::query()->find($pid->user_id);
                $user_id = $user->id;
                $group_id = $user->group_id;
                $oldgroup_id = $user->oldgroup_id;
            } else {
                $user_id = null;
                $group_id = null;
                $oldgroup_id = null;
            }
            $item = [
                'user_id'         => $user_id,
                'group_id'          => $group_id,
                'oldgroup_id'       => $oldgroup_id,
                'ordernum'          => $result->orderId,
                'title'             => $result->skuList[0]->skuName,
                'itemid'            => $result->skuList[0]->skuId,
                'count'             => $result->skuList[0]->skuNum,
                'price'             => $result->skuList[0]->price,
                'final_price'       => $result->skuList[0]->payPrice,
//                'commission_rate'   => $result->promotion_rate / 10,
//                'commission_amount' => $result->promotion_amount / 100,
                'pid'               => $p_id,
//                'status'            => $this->getPDDStatus($result->order_status),
                'type'              => 2,
                'complete_at'       => $result->finishTime ? date('Y-m-d H:i:s', substr($result->finishTime, 0, -3)) : null,
                'created_at'        => $result->orderTime ? date('Y-m-d H:i:s', substr($result->orderTime, 0, -3)) : null,
                'updated_at'       => now()->toDateTimeString(),
            ];
            switch ($result->validCode) {
                case 16:
                    $status = 1;
                    break;
                case 15:
                    $status = 3;
                    break;
                case  17:
                    $status = 1;
                    break;
                case 18:
                    $status = 2;
                    break;
                default:
                    $status = 3;
                    break;
            }

            $item['status'] = $status;
            $commission_rate = 0;
            $commission_amount = 0;
            foreach ($result->skuList as $value) {
                $commission_rate += $value->commissionRate;
                $commission_amount += $value->estimateFee;
            }
            $item['commission_rate'] = $commission_rate;
            $item['commission_amount'] = $commission_amount;

            db('tbk_orders')->updateOrInsert([
                'ordernum' => $item['ordernum'],
                'itemid' => $item['itemid'],
                'type' => 2,
            ], $item);
            $item = [];
        }
    }
}
