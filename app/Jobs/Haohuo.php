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
        foreach ($data as $v){
            $insert['content'] = $v->content;
            $insert['introduce'] = $v->show_text;
            $insert['app_hot_image'] = $v->app_hot_image;
            $insert['shares'] = $v->share_times;
            $insert['text'] = $v->copy_text;
            $insert['start_time'] = date('Y-m-d H:i:s',$v->activity_start_time);
            $insert['end_time'] = date('Y-m-d H:i:s',$v->activity_end_time);
            $items = [];
            foreach ($v->item_data as $val){
                if($val->product_id != 0){
                    $items['itemid'] = $val->itemid;
                    $items['itemtitle'] = $val->itemtitle;
                    $items['itemprice'] = $val->itemprice;//在售价
                    $items['itemendprice'] = $val->itemendprice;//券后价
                    $items['itempic'] = $val->itempic;//宝贝主图原始图像
                }
            }
            $insert['items'] = json_encode($items);
            $insert['created_at'] = Carbon::now()->toDateTimeString();
            $insert['updated_at'] = Carbon::now()->toDateTimeString();
            db('tbk_haohuo')->updateOrInsert(
                ['title'=>$v->name],$insert
            );
        }
    }
}
