<?php

namespace App\Jobs\Spider;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateItem implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $results;
    /**
     * Create a new job instance.
     *
     * @return void
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
        foreach ($this->results as $val)
        {
            $data = [];
            $data['volume'] = $val->itemsale;
            $data['price'] = $val->itemprice;
            $data['final_price'] = $val->itemendprice;
            $data['commission_rate'] = $val->tkrates;
            $data['end_time'] = date('Y-m-d H:i:s',$val->couponendtime);
            $data['updated_at'] = Carbon::now()->toDateTimeString();
            db('tbk_coupons')->where('item_id',$val->itemid)->update($data);
        }
    }
}
