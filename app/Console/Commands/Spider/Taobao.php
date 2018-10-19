<?php

namespace App\Console\Commands\Spider;

use App\Jobs\Haohuo;
use App\Jobs\SaveGoods;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

class Taobao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:tb {name? : The name of the spider} {--type=3} {--all=true}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '大淘客爬虫';

    /**
     * @var
     */
    protected $tbk;

    /**
     * Taobao constructor.
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

        $name = $this->argument('name');
        switch ($name)
        {
            case 'haohuo':
                $this->haohuo();
                break;
            case 'danpin':
                $this->danpin();
                break;
            case 'zhuanti':
                $this->zhuanti();
                break;
            case 'kuaiqiang':
                $this->kuaiqiang();
                break;
            case 'timingItems':
                $this->timingItems();
                break;
            case 'updateCoupon':
                $this->updateCoupon();
                break;
            case 'deleteCoupon':
                $this->deleteCoupon();
                break;
            default:
                $this->all();
                break;
        }



    }


    protected function all()
    {
        //数据类型
        $type = $this->option('type');
        //是否爬取所有
        $all = $this->option('all');

        $this->info('正在爬取大淘客优惠券');
        //开始爬取
        try {

            $totalPage = 9999999;
            if ($all == 'false') {
                $totalPage = 3;
            }

            $total = $totalPage * 100;
            $this->info("优惠券总数:{$total}");
            $this->info("总页码:{$totalPage}");
            $bar = $this->output->createProgressBar($totalPage);

            $min_id = 1;
            for ($i = 1; $i <= $totalPage; $i++) {

                $response = $this->tbk->spider([
                    'type' => $type,
                    'min_id' => $min_id,
                ]);
                if ($response['code'] != 1001) {
                    $this->warn($response['message']);
                    return ;
                }
                $result = $response['data'];
                if ($result) {

                    SaveGoods::dispatch($result['data'], 'taobao', $type, $all);
                }
                $min_id = $result['min_id'];
                $bar->advance();
                $this->info(" >>>已采集完第{$i}页");

            }
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    protected function haohuo()
    {
        // 好货专场
        $this->info('正在爬取好货专场');
        $totalPage = 999999;
        $bar = $this->output->createProgressBar($totalPage);
        $min_id = 1;
        for ($i=1;$i<$totalPage;$i++){
            $this->info($min_id);
            $result = $this->tbk->haohuo(['min_id'=>$min_id]);
            $result = json_decode($result);
            if($result->code != 1){
                return;
            }
            // 队列
            Haohuo::dispatch($result->data);
            $min_id = $result->min_id;
            $bar->advance();
            $this->info(">>>已采集完第{$i}页 ");
        }
        $bar->finish();
    }


    protected function danpin()
    {
        // 精选单品
        $total = 50;
        $this->info('正在爬取精选单品！');
        $bar = $this->output->createProgressBar($total);
        $min_id = 1;
        for ($i=1;$i <= $total; $i++) {

            $rest = $this->tbk->danpin(['min_id'=>$min_id]);
            if ($rest['code'] != 1001) {
                $this->warn($rest['message']);
                return ;
            }
            // 队列
            $data = data_get($rest,'data.data');
            \App\Jobs\Spider\JingxuanDp::dispatch($data);

            $min_id = data_get($rest,'data.min_id');

            $bar->advance();
            $this->info(">>>已采集完第{$total}页 ");
        }
        $bar->finish();
    }

    protected function zhuanti()
    {
        //TODO 精选专题，代码从tools搬过来
    }

    protected function kuaiqiang()
    {
        //TODO 精选专题，代码从tools搬过来
    }

    protected function timingItems()
    {
        //定时拉取
        $totalPage = 50;
        $bar = $this->output->createProgressBar($totalPage);
        $min_id = 1;
        for ($i=1;$i<$totalPage;$i++){
            $this->info($min_id);
            $results = $this->tbk->timingItems(['min_id'=>$min_id]);
            $results = json_decode($results);
            if($results->code != 1){
                return;
            }
            // 队列
            SaveGoods::dispatch($results->data,'timingItems');
            $min_id = $results->min_id;
            $bar->advance();
            $this->info(">>>已采集完第{$i}页 ");
        }
        $bar->finish();
    }

    protected function updateCoupon()
    {
        //TODO 商品更新，代码从tools搬过来
    }

    protected function deleteCoupon()
    {
        //TODO 失效商品，代码从tools搬过来
    }
}
