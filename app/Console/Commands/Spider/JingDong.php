<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use App\Jobs\SaveOrders;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

class JingDong extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:jd {name? : The name of the spider} {--all=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '京东优惠券爬虫';

    /**
     * @var TBKInterface
     */
    protected $tbk;

    /**
     * JingDong constructor.
     * @param TBKInterface $tbk
     */
    public function __construct(TBKInterface $tbk)
    {
        $this->tbk = $tbk;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument ('name');
        switch ($name) {
            case 'order':
                $this->order();
                break;
            default:
                $this->all();
                break;
        }
    }

    /**
     * 获取全网优惠卷
     */
    public function all()
    {
        try{
            // http://www.jingtuitui.com/  账号密码 15538762226  372945452zz

            $this->info('正在爬取京推推优惠券');
            $result = $this->tbk->spider([
                'page' => 1
            ]);

            $totalPage = data_get($result, 'data.totalPage', 1);

            $this->info("总页码:{$totalPage}");
            $bar = $this->output->createProgressBar($totalPage);

            for ($page = 1; $page <= $totalPage; $page++) {
                $response = $this->tbk->spider(['page'=>$page]);

                if ($response) {
                    SaveGoods::dispatch($response['data'], 'jingdong');
                }
                $bar->advance();
                $this->info(">>>已采集完第{$page}页 ");
            }
            $bar->finish();
        }catch (\Exception $e){
            $this->warn ($e->getMessage ());
        }


    }

    /**
     * 订单
     */
    public function order()
    {
        try {
            $bar = $this->output->createProgressBar(10);
            //循环所有页码查出数据
            for ($page=1;$page<=10;$page++){
                $resp = $this->tbk->getOrders(['page'=>10]);
                //写入队列

                SaveOrders::dispatch($resp['data'],'jingdong');
                $bar->advance();
                $this->info(">>>已采集完第{$page}页 ");
            }
            $bar->finish();
        }catch(\Exception $e){
            $this->warn ($e->getMessage ());
        }

    }
}
