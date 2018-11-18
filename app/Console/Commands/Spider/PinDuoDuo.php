<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use App\Jobs\SaveOrders;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

class PinDuoDuo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:pdd {name? : The name of the spider} {--all=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '拼多多优惠券爬虫';

    /**
     * @var TBKInterface
     */
    protected $tbk;

    /**
     * PinDuoDuo constructor.
     * @param tbkInterface $tbk
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
        $name = $this->argument('name');
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
     * 获取全网优惠卷.
     */
    public function all()
    {
        try {
            // 拼多多怕爬虫 爬取多多进宝 http://jinbao.pinduoduo.com

            $this->info('正在爬取拼多多优惠券');
            $result = $this->tbk->spider([
                'page' => 1,
            ]);

            $total = data_get($result, 'data.total_count', 0);
            $totalPage = (int) ceil($total / 100) > 600 ? 600 : (int) ceil($total / 100);

            $this->info("优惠券总数:{$total}");
            $bar = $this->output->createProgressBar($totalPage);

            for ($page = 1; $page <= $totalPage; $page++) {
                $response = $this->tbk->spider(['page'=>$page]);

                $goods_list = data_get($response, 'data.goods_list', 0);

                if ($goods_list) {
                    SaveGoods::dispatch($goods_list, 'pinduoduo');
                }

                $bar->advance();
                $this->info(" >>>已采集完第{$page}页");
            }
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 拼多多订单.
     */
    public function order()
    {
        try {
            $resp = $this->tbk->getOrders();

            $total_count = data_get($resp, 'total_count', 1);

            $totalPage = (int) ceil($total_count / 100);

            $this->info("总页码:{$totalPage}");
            $bar = $this->output->createProgressBar($totalPage);

            for ($page = 1; $page <= $totalPage; $page++) {
                $response = $this->tbk->getOrders(['page'=>$page]);

                $data = data_get($response, 'order_list', null);

                if ($data) {
                    SaveOrders::dispatch($data, 'pinduoduo');
                }
                $bar->advance();
                $this->info(">>>已采集完第{$page}页 ");
            }
            $bar->finish();
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }
}
