<?php

namespace App\Jobs\Spider;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class KuaiQiang implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var
     */
    protected $kuaiqiang;
    /**
     * @var
     */
    protected $hour;

    /**
     * KuaiQiang constructor.
     * @param $kuaiqiang
     * @param $hour
     */
    public function __construct($kuaiqiang, $hour)
    {
        $this->kuaiqiang = $kuaiqiang;
        $this->hour = $hour;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->kuaiqiang as $val) {
            $data = [];
            $data['itemid'] = $val->itemid;
            $data['title'] = $val->itemtitle;
            $data['short_title'] = $val->itemshorttitle;
            $data['introduce'] = $val->itemdesc;
            $data['final_price'] = $val->itemendprice;
            $data['price'] = $val->itemprice;
            $data['sales'] = $val->itemsale;
            $data['today_sale'] = $val->todaysale;
            $data['pic_url'] = $val->itempic;
            $data['shop_type'] = $val->shoptype;
            $data['coupon_price'] = $val->couponmoney;
            $data['video_url'] = $val->material_info->main_video_url;
            $data['commission_rate'] = $val->tkrates;
            $data['start_time'] = date('Y-m-d H:i:s', $val->start_time);
            $data['type'] = $val->grab_type;
            $data['hour_type'] = $this->hour;
            $data['created_at'] = now()->toDateTimeString();
            $data['updated_at'] = now()->toDateTimeString();

            db('tbk_kuaiqiang')->updateOrInsert(['itemid'=>$data['itemid']], $data);
        }
    }
}
