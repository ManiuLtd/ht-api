<?php

namespace App\Jobs\Spider;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class JingXuan implements ShouldQueue
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
     * JingXuan constructor.
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
        foreach ($this->results as $result) {
            $item = [
                'itemid'          => $result->itemid,
                'title'           => $result->title,
                'pic_url'         => json_encode($result->itempic),
                'content'         => $result->content,
                'price'           => $result->itemprice,
                'final_price'     => $result->itemendprice,
                'coupon_price'    => $result->couponmoney,
                'commission_rate' => $result->tkrates,
                'shares'          => $result->dummy_click_statistics,
                'comment2'        => html_entity_decode($result->copy_content),
                'comment1'        => $result->copy_comment,
                'show_at'         => date('Y-m-d H:i:s', $result->show_time),
                'created_at'      => now()->toDateTimeString(),
                'updated_at'      => now()->toDateTimeString(),
            ];

            DB::table('tbk_jingxuan')->updateOrInsert([
                'title' => $item['title'],
            ], $item);
        }
    }
}
