<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

class PinDuoDuo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:pdd';

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
        // 拼多多怕爬虫 爬取多多进宝 http://jinbao.pinduoduo.com

        $this->info('正在爬取拼多多优惠券');
        $result = $this->tbk->spider();

        if ($result['code'] == 4004) {
            $this->warn($result['message']);

            return;
        }

        $total = data_get($result, 'data.total_count', 0);
        $totalPage = (int) ceil($total / 100) > 600 ? 600 : (int) ceil($total / 100);

        $this->info("优惠券总数:{$total}");
        $bar = $this->output->createProgressBar($totalPage);

        for ($page = 1; $page <= $totalPage; $page++) {
            $response = $this->tbk->spider(['page'=>$page]);

            if ($response['code'] == 4004) {
                $this->warn($response['message']);

                return;
            }
            $goods_list = data_get($response, 'data.goods_list', 0);

            if ($goods_list) {
                SaveGoods::dispatch($goods_list, 'pinduoduo');
            }

            $bar->advance();
            $this->info(" >>>已采集完第{$page}页");
        }
    }
}
