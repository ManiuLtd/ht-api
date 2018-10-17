<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveOrders;
use App\Tools\Taoke\TBKInterface;
use Illuminate\Console\Command;

class JingDongOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:jd-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '京东订单获取';
    /**
     * @var
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
        $bar = $this->output->createProgressBar(10);
        //循环所有页码查出数据
        for ($page=1;$page<=10;$page++){
            $resp = $this->TBK->getOrders(['page'=>10]);
            //写入队列
            if ($resp['code'] != 1001) {
                $this->warn($resp['message']);
                return;
            }
            SaveOrders::dispatch($resp['data'],'jingdong');
            $bar->advance();
            $this->info(">>>已采集完第{$page}页 ");
        }
        $bar->finish();
    }
}
