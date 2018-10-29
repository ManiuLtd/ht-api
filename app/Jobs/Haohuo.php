<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class Haohuo implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $result;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->result;
        foreach ($data as $v) {
            $items = [];
            foreach ($v->item_data as $key => $val) {
                if ($val->product_id != 0) {
                    $items[$key]['itemid']      = $val->itemid;
                    $items[$key]['title']       = $val->itemtitle;
                    $items[$key]['price']       = $val->itemprice; //在售价
                    $items[$key]['final_price'] = $val->itemendprice; //券后价
                    $items[$key]['pic_url']     = $val->itempic; //宝贝主图原始图像
                    $items[$key]['type']        = 1; //宝贝主图原始图像
                }
            }
            $insert = [
                'title'         => $v->name,
                'app_hot_image' => $v->app_hot_image,
                'shares'        => $v->share_times,
                'text'          =>  html_entity_decode($v->copy_text),
                'start_time'    => date('Y-m-d H:i:s', $v->activity_start_time),
                'end_time'      => date('Y-m-d H:i:s', $v->activity_end_time),
                'items'         => json_encode($items),
                'created_at'    => Carbon::now()->toDateTimeString(),
                'updated_at'    => Carbon::now()->toDateTimeString(),
            ];
            db('tbk_haohuo')->updateOrInsert([
                'title' => $v->name,
            ], $insert);
        }
    }
}
