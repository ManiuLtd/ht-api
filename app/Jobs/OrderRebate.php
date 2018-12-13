<?php

namespace App\Jobs\Spider;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use App\Events\CreditOrderFriend;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class OrderRebate implements ShouldQueue
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
            event(new CreditOrderFriend([
                'user_id' => $result['user_id']
            ],1));
            event(new \App\Events\OrderRebate([
                'user_id' => $result['user_id'],
                'price'   => $result['final_price'],
            ]));
        }
    }
}
