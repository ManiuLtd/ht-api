<?php

namespace App\Jobs\Spider;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class JingxuanDp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    /**
     * @var int
     */
    public $tries = 1;
    /**
     * @var
     */
    protected $results;


    /**
     * JingxuanDp constructor.
     * @param $results
     */
    public function __construct($results)
    {
        $this->results = $results;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        foreach ($this->results as $result){
            $item = [
                'itemid' => $result->itemid,
                'title' => $result->title,
                'pic_url' => json_encode($result->itempic),
                'content' => $result->content,
                'price' => $result->itemprice,
                'final_price' => $result->itemendprice,
                'coupon_price' => $result->couponmoney,
                'commission_rate' => $result->tkrates,
                'shares' => $result->dummy_click_statistics,
                'show_content' => $result->show_content,
                'copy_content' => $result->copy_content,
                'show_comment' => $result->show_comment,
                'copy_comment' => $result->copy_comment,
                'show_at' => date('Y-m-d H:i:s',$result->show_time),
                'created_at' => now()->toDateTimeString(),
                'updated_at' => now()->toDateTimeString(),

            ];

            DB::table('tbk_jingxuan')->updateOrInsert([
                'title' => $item['title'],
            ],$item);
            $item = [];
        }
    }
}
