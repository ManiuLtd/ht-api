<?php

namespace App\Console\Commands\Spider;

use App\Jobs\Spider\DownItem;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

class DownItems extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'down-items';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '失效商品列表';

    /**
     * @var TBKInterface
     */
    protected $TBK;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TBKInterface $TBK)
    {
        $this->TBK = $TBK;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $end   = date('H');
        if ($end == 0){
            $end   = 23;
        }
        $start = $end - 1;
        $rest = $this->TBK->deleteCoupon([
            'start' => $start,
            'end'   => $end
        ]);
        // 队列
        DownItem::dispatch(data_get($rest,'data.data'));
    }
}
