<?php

namespace App\Jobs\Spider;

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
            $data['sales'] = $val->itemsale;
            $data['sales'] = $val->itemsale;
            $data['today_sale'] = $val->todaysale;
            $data['updated_at'] = now();
            db('tbk_kuaiqiang')->where('itemid',$val->itemid)->update($data);
        }
    }
}
