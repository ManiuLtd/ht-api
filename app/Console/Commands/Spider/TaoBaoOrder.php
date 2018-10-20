<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use App\Jobs\SaveOrders;
use App\Tools\Taoke\TBKInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TaoBaoOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:tb-order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '淘宝订单爬取';

    /**
     * @var
     */
    protected $TBK;

    /**
     * TaoBaoOrder constructor.
     * @param TBKInterface $TBK
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
        try {
            $sids = DB::table('tbk_oauth')->where('type', 1)->get();
            $sid_arr = $sids->toArray();
            $bar = $this->output->createProgressBar(10 * count($sid_arr));
            foreach ($sids as $sid) {

//        $this->info("总页码:{10}");

                //循环所有页码查出数据
                for ($page = 1; $page <= 10; $page++) {
                    $resp = $this->TBK->getOrders([
                        'page' => 10,
                        'sid' => $sid->sid,
                    ]);
                    //写入队列
                    if ($resp['code'] != 1001) {
                        $this->warn($resp['message']);
                        return;
                    }
                    SaveOrders::dispatch($resp['data'], 'taobao');
                    $bar->advance();
                    $this->info(">>>已采集完第{$page}页 ");
                }

            }
            $bar->finish();
        }catch (\Exception $e) {
            $this->warn($e->getMessage());
        }

    }
}
