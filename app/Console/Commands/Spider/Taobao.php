<?php

namespace App\Console\Commands\Spider;

use App\Jobs\Haohuo;
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
    protected $description = '好单库爬虫';

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
        parent::__construct ();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $name = $this->argument ('name');
        switch ($name) {
            case 'haohuo':
                $this->haohuo ();
                break;
            case 'danpin':
                $this->danpin ();
                break;
            case 'zhuanti':
                $this->zhuanti ();
                break;
            case 'kuaiqiang':
                $this->kuaiqiang ();
                break;
            case 'timingItems':
                $this->timingItems ();
                break;
            case 'updateCoupon':
                $this->updateCoupon ();
                break;
            case 'deleteCoupon':
                $this->deleteCoupon ();
                break;
            default:
                $this->all ();
                break;
        }
    }

    /**
     * 全网优惠券
     */
    protected function all()
    {
        //数据类型
        $type = $this->option ('type');
        //是否爬取所有
        $all = $this->option ('all');

        $this->info ('正在爬取好单库优惠券');
        //开始爬取
        try {

            $totalPage = 1000;
            if ($all == 'false') {
                $totalPage = 3;
            }

            $this->info ("总页码:{$totalPage}");
            $bar = $this->output->createProgressBar ($totalPage);

            $min_id = 1;
            for ($i = 1; $i <= $totalPage; $i++) {

                $response = $this->tbk->spider ([
                    'type' => $type,
                    'min_id' => $min_id,
                ]);


                if ($response) {
                    SaveGoods::dispatch ($response['data'], 'taobao', $type, $all);
                }
                $min_id = $response['min_id'];
                $bar->advance ();
                $this->info (" >>>已采集完第{$i}页");

            }
        } catch (\Exception $e) {
            $this->warn ($e->getMessage ());
        }
    }

    /**
     * 好货专场
     * @throws \Exception
     */
    protected function haohuo()
    {
        // 好货专场
        try{
            $this->info ('正在爬取好货专场');
            $totalPage = 1000;
            $bar = $this->output->createProgressBar ($totalPage);
            $min_id = 1;
            for ($i = 1; $i < $totalPage; $i++) {
                $this->info ($min_id);
                $result = $this->tbk->haohuo (['min_id' => $min_id]);

                // 队列
                Haohuo::dispatch ($result->data);
                $min_id = $result->min_id;
                $bar->advance ();
                $this->info (">>>已采集完第{$i}页 ");
            }
            $bar->finish ();
        }catch (\Exception $e){
            $this->warn ($e->getMessage ());
        }
    }


    /**
     * 精选单品
     */
    protected function danpin()
    {
        try{
            $total = 50;
            $this->info ('正在爬取精选单品！');
            $bar = $this->output->createProgressBar ($total);
            $min_id = 1;
            for ($i = 1; $i <= $total; $i++) {

                $resp = $this->tbk->danpin (['min_id' => $min_id]);

                // 队列
                \App\Jobs\Spider\JingxuanDp::dispatch ($resp['data']);

                $min_id = $resp['min_id'];

                $bar->advance ();
                $this->info (">>>已采集完第{$total}页 ");
            }
            $bar->finish ();
        }catch (\Exception $e){
            $this->warn ($e->getMessage ());
        }
    }

    /**
     * 精选专题
     */
    protected function zhuanti()
    {
        $res = $this->tbk->zhuanti ();
        try {
            foreach ($res->data as $re) {
                $insert = [
                    'title' => $re->name,
                    'thumb' => $re->app_image,
                    'banner' => $re->image,
                    'content' => $re->content,
                    'start_time' => date ('Y-m-d H:i:s', $re->activity_start_time),
                    'end_time' => date ('Y-m-d H:i:s', $re->activity_end_time),
                    'created_at' => Carbon::now ()->toDateTimeString (),
                    'updated_at' => Carbon::now ()->toDateTimeString (),
                ];
                db ('tbk_zhuanti')->updateOrInsert ([
                    'title' => $re->name
                ], $insert);
            }
        } catch (\Exception $e) {
            $this->warn ($e->getMessage ());
        }
    }

    /**
     * 快抢商品
     */
    protected function kuaiQiang()
    {
        try{
            $total = 5;
            $bar = $this->output->createProgressBar ($total);
            $min_id = 1;
            for ($i = 1; $i <= $total; $i++) {

                $res = $this->tbk->kuaiQiang (['min_id' => $min_id]);


                // 队列
                Kuaiqiang::dispatch ($res['data']);
                $min_id = $res['min_id'];

                $bar->advance ();
                $this->info (">>>已采集完第{$total}页 ");
            }
            $bar->finish ();
        }catch (\Exception $e){
            $this->warn ($e->getMessage ());
        }
    }

    /**
     * 定时拉取
     */
    protected function timingItems()
    {
        try{
            $totalPage = 50;
            $bar = $this->output->createProgressBar ($totalPage);
            $min_id = 1;
            for ($i = 1; $i < $totalPage; $i++) {
                $this->info ($min_id);
                $results = $this->tbk->timingItems (['min_id' => $min_id]);
                SaveGoods::dispatch ($results['data'], 'timingItems');
                $min_id = $results['min_id'];
                $bar->advance ();
                $this->info (">>>已采集完第{$i}页 ");
            }
            $bar->finish ();
        }catch (\Exception $e){
            $this->warn($e->getMessage());
        }
    }

    /**
     * 商品更新
     */
    protected function updateCoupon()
    {
        try{
            $total = 50;
            $bar = $this->output->createProgressBar ($total);
            $min_id = 1;
            for ($i = 1; $i <= $total; $i++) {

                $res = $this->tbk->updateCoupon (['min_id' => $min_id]);

                // 队列

                \App\Jobs\Spider\UpdateItem::dispatch ($res->data);
                $min_id = $res->min_id;
                $bar->advance ();
                $this->info (">>>已采集完第{$total}页 ");
            }
            $bar->finish ();
        }catch (\Exception $e){
            $this->warn($e->getMessage());
        }
    }

    /**
     * 失效商品
     */
    protected function deleteCoupon()
    {
        try{
            $end = date ('H');
            if ($end == 0) {
                $end = 23;
            }
            $start = $end - 1;
            $rest = $this->tbk->deleteCoupon ([
                'start' => $start,
                'end' => $end
            ]);
            // 队列
            DownItem::dispatch ($rest->data);
        }catch (\Exception $e){
            $this->warn($e->getMessage());
        }
    }
}
