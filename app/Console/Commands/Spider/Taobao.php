<?php

namespace App\Console\Commands\Spider;

use App\Models\System\Setting;
use Carbon\Carbon;
use App\Jobs\Haohuo;
use App\Jobs\SaveGoods;
use App\Jobs\SaveOrders;
use App\Jobs\Spider\DownItem;
use App\Jobs\Spider\KuaiQiang;
use Illuminate\Console\Command;
use App\Tools\Taoke\TBKInterface;

class Taobao extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'spider:tb {name? : The name of the spider} {--type=3} {--all=true} {--h=1}';

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
        parent::__construct();
    }

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $name = $this->argument('name');
        switch ($name) {
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
            case 'order':
                $this->getOrders();
                break;
            default:
                $this->all();
                break;
        }
    }

    /**
     * 全网优惠券.
     */
    protected function all()
    {
        //数据类型
        $type = $this->option('type');
        //是否爬取所有
        $all = $this->option('all');

        $this->info('正在爬取好单库优惠券');
        //开始爬取
        try {
            $totalPage = 1000;
            if ($all == 'false') {
                $totalPage = 3;
            }

            $this->info("总页码:{$totalPage}");
            $bar = $this->output->createProgressBar($totalPage);

            $min_id = 1;
            for ($i = 1; $i <= $totalPage; $i++) {
                $response = $this->tbk->spider([
                    'type' => $type,
                    'min_id' => $min_id,
                ]);

                if ($response) {
                    SaveGoods::dispatch($response['data'], 'taobao', $type, $all);
                }
                $min_id = $response['min_id'];
                $bar->advance();
                $this->info(" >>>已采集完第{$i}页");
            }
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 好货专场.
     * @throws \Exception
     */
    protected function haohuo()
    {
        // 好货专场
        try {
            $this->info('正在爬取好货专场');
            $totalPage = 1000;
            $bar = $this->output->createProgressBar($totalPage);
            $min_id = 1;
            for ($i = 1; $i < $totalPage; $i++) {
                $this->info($min_id);
                $result = $this->tbk->haohuo(['min_id' => $min_id]);
                // 队列
                if ($result->min_id != $min_id) {
                    Haohuo::dispatch($result->data);
                    $min_id = $result->min_id;
                    $this->info($min_id);
                    $bar->advance();
                    $this->info(">>>已采集完第{$i}页 ");
                }
            }
            $bar->finish();
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 精选单品
     */
    protected function danpin()
    {
        try {
            $total = 50;
            $this->info('正在爬取精选单品！');
            $bar = $this->output->createProgressBar($total);
            $min_id = 1;
            for ($i = 1; $i <= $total; $i++) {
                $resp = $this->tbk->danpin(['min_id' => $min_id]);
                if ($min_id != $resp['min_id']) {
                    // 队列
                    \App\Jobs\Spider\JingXuan::dispatch($resp['data']);
                    $min_id = $resp['min_id'];
                    $bar->advance();
                    $this->info(">>>已采集完第{$total}页 ");
                }
            }
            $bar->finish();
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 精选专题.
     */
    protected function zhuanti()
    {
        $res = $this->tbk->zhuanti();
        try {
            foreach ($res->data as $re) {
                $items = $this->tbk->zhuantiItem([
                    'id' => $re->id
                ]);
                $data = [];
                foreach ($items->data as $k => $v){
                    $data[$k]['title']        = $v->itemtitle;//标题
                    $data[$k]['short_title']  = $v->itemshorttitle;//短标题
                    $data[$k]['itemid']       = $v->itemid;
                    $data[$k]['price']        = $v->itemprice;//在售价
                    $data[$k]['coupon_price'] = $v->itemendprice;//卷后价
                    $data[$k]['pic_url']      = $v->itempic;//图片
                    $data[$k]['type']         = 1;

                }
                $insert = [
                    'special_id' => $re->id,
                    'title'      => $re->name,
                    'thumb'      => 'http://img.haodanku.com/'.$re->app_image,
                    'banner'     => 'http://img.haodanku.com/'.$re->image,
                    'content'    => $re->content,
                    'items'      => json_encode($data),
                    'start_time' => date('Y-m-d H:i:s', $re->activity_start_time),
                    'end_time'   => date('Y-m-d H:i:s', $re->activity_end_time),
                    'created_at' => Carbon::now()->toDateTimeString(),
                    'updated_at' => Carbon::now()->toDateTimeString(),
                ];
                db('tbk_zhuanti')->updateOrInsert([
                    'title' => $re->name,
                ], $insert);
            }
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 快抢商品
     */
    protected function kuaiQiang()
    {
        try {
            $total = 5;
            $bar = $this->output->createProgressBar($total);
            $min_id = 1;
            for ($j=1;$j<=15;$j++){
                for ($i = 1; $i <= $total; $i++) {
                    $res = $this->tbk->kuaiQiang(['min_id' => $min_id,'hour_type'=>$j]);
                    $this->info($j);
                    if ($min_id != $res['min_id']) {
                        // 队列
                        KuaiQiang::dispatch($res['data'],$j);
                        $min_id = $res['min_id'];
                        $bar->advance();
                        $this->info(">>>已采集完第{$total}页 ");
                    }
                }
            }
            $bar->finish();
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 定时拉取.
     */
    protected function timingItems()
    {
        try {
            $totalPage = 50;
            $bar = $this->output->createProgressBar($totalPage);
            $min_id = 1;
            for ($i = 1; $i < $totalPage; $i++) {
                $this->info($min_id);
                $results = $this->tbk->timingItems(['min_id' => $min_id]);
                if ($results['min_id'] != $min_id) {
                    //队列
                    SaveGoods::dispatch($results['data'], 'timingItems');
                    $min_id = $results['min_id'];
                    $bar->advance();
                    $this->info(">>>已采集完第{$i}页 ");
                }
            }
            $bar->finish();
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 商品更新.
     */
    protected function updateCoupon()
    {
        try {
            $total = 50;
            $bar = $this->output->createProgressBar($total);
            $min_id = 1;
            for ($i = 1; $i <= $total; $i++) {
                $res = $this->tbk->updateCoupon(['min_id' => $min_id]);
                if ($min_id != $res['min_id']) {
                    // 队列
                    \App\Jobs\Spider\UpdateItem::dispatch($res['data']);
                    $min_id = $res['min_id'];
                    $bar->advance();
                    $this->info(">>>已采集完第{$min_id}页 ");
                }
            }
            $bar->finish();
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 失效商品
     */
    protected function deleteCoupon()
    {
        try {
            $end = date('H');
            if ($end == 0) {
                $end = 23;
            }
            $start = $end - 1;
            $rest = $this->tbk->deleteCoupon([
                'start' => $start,
                'end' => $end,
            ]);
            // 队列
            DownItem::dispatch($rest);
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }

    /**
     * 获取订单.
     */
    protected function getOrders()
    {
        try {
            $setting = setting(1);
            $sid = json_decode($setting->taobao)->sid ?? 1942;
            $type = $this->option('type');
            $bar = $this->output->createProgressBar(4);
            //循环所有页码查出数据
            for ($page = 1; $page <= 4; $page++) {
                $resp = $this->tbk->getOrders([
                    'page' => $page,
                    'sid' => $sid,
                    'type' => $type,
                ]);

                //写入队列
                SaveOrders::dispatch($resp, 'taobao');
                $bar->advance();
                $this->info(">>>已采集完第{$page}页 ");
            }


            $bar->finish();
        } catch (\Exception $e) {
            $this->warn($e->getMessage());
        }
    }
}
