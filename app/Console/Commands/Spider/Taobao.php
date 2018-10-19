<?php

namespace App\Console\Commands\Spider;

use App\Jobs\SaveGoods;
use App\Jobs\Spider\DownItem;
use App\Jobs\Spider\Kuaiqiang;
use Carbon\Carbon;
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
       //TODO 好货专场，代码从tools搬过来
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
        //精选专题
        $res = $this->tbk->zhuanti();
        try {
            foreach ($res->data as $re){
                $insert = [
                    'title'      => $re->name,
                    'thumb'      => $re->app_image,
                    'banner'     => $re->image,
                    'content'    => $re->content,
                    'start_time' => date('Y-m-d H:i:s',$re->activity_start_time),
                    'end_time'   => date('Y-m-d H:i:s',$re->activity_end_time),
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
                db('tbk_zhuanti')->updateOrInsert([
                    'title' => $re->name
                ],$insert);
            }
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    protected function kuaiqiang()
    {
        $total = 5;
        $bar = $this->output->createProgressBar($total);
        $min_id = 1;
        for ($i=1;$i <= $total; $i++) {

            $rest = $this->tbk->KuaiqiangShop(['min_id'=>$min_id]);
            if ($rest['code'] != 1001) {
                $this->warn($rest['message']);

                return ;
            }
            // 队列
            $data = data_get($rest,'data.data');
            Kuaiqiang::dispatch($data);
            $min_id = data_get($rest,'data.min_id');

            $bar->advance();
            $this->info(">>>已采集完第{$total}页 ");
        }
        $bar->finish();
    }

    protected function timingItems()
    {
        //TODO 定时拉取，代码从tools搬过来
    }

    protected function updateCoupon()
    {
        $total = 50;
        $bar = $this->output->createProgressBar($total);
        $min_id = 1;
        for ($i=1;$i <= $total; $i++) {

            $rest = $this->tbk->updateCoupon(['min_id'=>$min_id]);
            if ($rest['code'] != 1001) {
                $this->warn($rest['message']);

                return ;
            }
            // 队列
            $data = data_get($rest,'data.data');
            \App\Jobs\Spider\UpdateItem::dispatch($data);

            $min_id = data_get($rest,'data.min_id');

            $bar->advance();
            $this->info(">>>已采集完第{$total}页 ");
        }
        $bar->finish();
    }

    protected function deleteCoupon()
    {
        //失效商品
        $end   = date('H');
        if ($end == 0){
            $end   = 23;
        }
        $start = $end - 1;
        $rest = $this->tbk->deleteCoupon([
            'start' => $start,
            'end'   => $end
        ]);
        // 队列
        DownItem::dispatch($rest->data);
    }
}
