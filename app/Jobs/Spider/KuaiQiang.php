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
    protected $kuaiqiang;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($kuaiqiang)
    {
        $this->kuaiqiang = $kuaiqiang;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->kuaiqiang as $val)
        {
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
//            $data['video_id'] = $val->videoid;
            $data['video_url'] = $val->material_info->main_video_url;
//            $data['activity_type'] = $val->activity_type;
            $data['commission_rate'] = $val->tkrates;
//            $data['coupon_start_time'] = $val->couponstarttime;
//            $data['coupon_end_time'] = $val->couponendtime;
            $data['start_time'] =date('Y-m-d H:i:s',$val->start_time);
//            $data['end_time'] = $val->end_time;
            $data['type'] = $val->grab_type;
            $data['created_at'] = Carbon::now()->toDateTimeString();
            db('tbk_kuaiqiang')->updateOrInsert(['itemid'=>$data['itemid']],$data);
        }
    }
}
